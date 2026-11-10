<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Rules\DiagnosticosDebenTenerCamposCompletos;
use Carbon\Carbon;

class AtencionEmergenciaFormRequest extends Request
{
    public function all()
    {
        $data = parent::all();
        //dd($data);
        $data["esta_en_gestacion"] = $data["esta_en_gestacion"] === "true" ? true : false;
        $data["signos_vitales_atencion_emergencia"] = $data["signos_vitales_atencion_emergencia"] === "true" ? true : false;
        if (isset($data['esta_gestando'])) {
            $data['esta_gestando'] = $data['esta_gestando'] === 'true' ? true : false;
        }
        $data['esta_en_puerperio'] = $data['esta_en_puerperio'] === "true" ? true : false; // --> PUEPEPERA
        $data["interconsulta_activo"] = $data["interconsulta_activo"] === "true" ? true : false; // --> INTERCONSULTA
        $data["laboratorio_activo"] = $data["laboratorio_activo"] === "true" ? true : false; // --> LABORATORIO
        $data["imagenologia_activo"] = $data["imagenologia_activo"] === "true" ? true : false; // --> IMAGENOLOGIA
        $data["tiene_receta"] = $data["tiene_receta"] === "true" ? true : false; // --> RECETA
        //dd($data);
        return $data;
    }

    public function attributes()
    {
        $attributes = [
            "temperatura" => "TEMPERATURA",
            "talla" => "TALLA",
            "peso" => "PESO",
            "imc" => "IMC",
            "peso_x_altura" => "PESO IDEAL",
            "saturacion" => "Sa02",
            "presion_arterial_x" => "PRESION ARTERIAL",
            "presion_arterial_y" => "PRESION ARTERIAL",
            "frecuencia_respiratoria" => "FREC. RESPIRATORIA",
            "frec_cardiaca" => "FREC. CARDIACA",
            "oxigenacion" => "Fi02",
            "nombre_procedimiento" => "PROCEDIMIENTO", // PROCEDIMIENTOS

            // ********* PUERPERA *********
            "numero_documento_puerpera" => "NEONATO", 
            "fecha_parto" => "FECHA PARTO", 
            "hora_parto" => "HORA PARTO", 
            "tipo_parto" => "TIPO DE PARTO", 
            "complicacion_parto" => "COMPLICACIÓN", 
            "descripcion_parto" => "DESCRIPCIÓN", 

            "id_servicio_interconsulta" => "INTERCONSULTA", // INTERCONSULTA
            "id_examen_lab" => "EXAMEN DE LABORATORIO", // LABORATORIO
            "id_examen_rad" => "EXAMEN IMAGENOLOGIA", // IMAGENOLOGIA
        ];

        // *************** PROCEDIMIENTOS ***************
        $arrayProcedimientos = $this->input('id_procedimiento', []);
        if (isset($arrayProcedimientos) && count($arrayProcedimientos) > 0) {
            foreach ($arrayProcedimientos as $key => $value) {
                $attributes['nombre_procedimiento.'.$key] = 'NOMBRE PROCEDIMIENTO';
                //$attributes['id_procedimiento.'.$key] = 'ID PROCEDIMIENTO';
                $attributes['procedimiento_cantidad.'.$key] = 'CANTIDAD';
                $attributes['miSelect.'.$key] = 'DIAGNÓSTICO';
            }    
        }

        // *************** PUEPEPERA ***************
        $arrayPuerpera = $this->input('numero_documento_puerpera', []);
        if (isset($arrayPuerpera) && count($arrayPuerpera) > 0) {
            foreach ($arrayPuerpera as $key => $value) {
                $attributes['estado_vida_puerpera.'.$key] = 'ESTADO DE VIDA';
                $attributes['tipo_documento_puerpera.'.$key] = 'TIPO DE DOCUMENTO';
                $attributes['numero_documento_puerpera.'.$key] = 'NUM. DOCUMENTO';
                $attributes['sexo_puerpera.'.$key] = 'SEXO';
                $attributes['talla_puerpera.'.$key] = 'TALLA';
                $attributes['peso_puerpera.'.$key] = 'PESO';
            }    
        }

        // *************** INTERCONSULTA ***************
        $arrayInterConsulta = $this->input('id_servicio_interconsulta', []);
        if (isset($arrayInterConsulta) && count($arrayInterConsulta) > 0) {
            foreach ($arrayInterConsulta as $key => $value) {
                // $attributes['nombre_servicio_interconsulta.'.$key] = 'SERVICIO';
                // $attributes['personal_salud.'.$key] = 'PERSONAL DE SALUD';
                $attributes['motivo_interconsulta.'.$key] = 'RESUMEN';
                $attributes['id_servicio_interconsulta.'.$key] = 'SERVICIO';
            }    
        }

        // *************** LABORATORIO ***************
        $arrayLaboratorio = $this->input('id_examen_lab', []);
        if (isset($arrayLaboratorio) && count($arrayLaboratorio) > 0) {
            foreach ($arrayLaboratorio as $key => $value) {
                $attributes['nombre_examen_lab.'.$key] = 'NOMBRE    ';
                // $attributes['id_examen_lab.'.$key] = 'PERSONAL DE SALUD';
                $attributes['id_examen_lab.'.$key] = 'PERSONAL DE SALUD';
                $attributes['examen_lab_observacion.'.$key] = 'OBSERVACIÓN';
                $attributes['examen_lab_cantidad.'.$key] = 'CANTIDAD';
                $attributes['miSelectLab.'.$key] = 'DIAGNÓSTICO';
            }    
        }

        // *************** IMAGENOLOGIA ***************
        $arrayImagenologia = $this->input('id_examen_rad', []);
        if (isset($arrayImagenologia) && count($arrayImagenologia) > 0) {
            foreach ($arrayImagenologia as $key => $value) {
                $attributes['nombre_examen_rad.'.$key] = 'NOMBRE';
                // $attributes['id_examen_rad.'.$key] = 'PERSONAL DE SALUD';
                $attributes['id_comparativo.'.$key] = 'COMPARATIVO';
                $attributes['id_lado.'.$key] = 'LADO';
                $attributes['examen_rad_observacion.'.$key] = 'OBSERVACIÓN';
                $attributes['examen_rad_cantidad.'.$key] = 'CANTIDAD';
                $attributes['miSelectRad.'.$key] = 'DIAGNÓSTICO';
            }    
        }

        return $attributes;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $temperatura = "";
        $talla = "";
        $peso = "";
        $imc = "";
        $peso_x_altura = "";
        $saturacion = "";
        $presion_arterial_x = "";
        $presion_arterial_y = "";
        $frecuencia_respiratoria = "";
        $frec_cardiaca = "";
        $oxigenacion = ""; 
        if ($this -> input("signos_vitales_atencion_emergencia")) {
            $temperatura = "numeric|min:35|max:40";
            $talla = "numeric|min:25|max:250";
            $peso = "numeric";
            // $imc = "required|numeric";
            //$peso_x_altura = "numeric";
            $saturacion = "numeric|min:60|max:100";
            $presion_arterial_x = "numeric";
            $presion_arterial_y = "numeric";
            $frecuencia_respiratoria = "numeric|min:10|max:35";
            $frec_cardiaca = "numeric|min:50|max:150";
            $oxigenacion = "numeric|min:21|max:100"; 
        }
        $rules = [
            "temperatura" => $temperatura,
            "talla" => $talla,
            "peso" => $peso,
            "imc" => $imc,
            //"peso_x_altura" => $peso_x_altura,
            "saturacion" => $saturacion,
            "presion_arterial_x" => $presion_arterial_x,
            "presion_arterial_y" => $presion_arterial_y,
            "frecuencia_respiratoria" => $frecuencia_respiratoria,
            "frec_cardiaca" => $frec_cardiaca,
            "oxigenacion" => $oxigenacion,
            'hora_parto' => 'required_if:esta_en_puerperio,true',
            'tipo_parto' => $this->input('esta_en_puerperio') === true ? 'required|integer|min:1' : '',
            'complicacion_parto' => 'required_if:esta_en_puerperio,true|string|min:0|max:140',
            'descripcion_parto' => 'sometimes|string|min:0|max:255',
            'esta_en_puerperio' => ['sometimes', 'boolean'],
        ];

        // Por si es proridad 4:
        $prioridadActual = $this->request->get('id_prioridad_actual');
        $flagRegistroAlta = $this->request->get('flag_registro_alta');
        if (intval($prioridadActual) != 4 && intval($flagRegistroAlta) != 1) {
            $rules["id_cama"] = "required|integer|min:1";
            //$rules["appt"] = ["required", "hora_actual_atencion:".$this->input('appt')];
            $rules["id_area_destino"] = "required|integer|min:1";
            $rules["id_prioridad"] = "required|integer|min:1";
            $rules["id_topico"] = "required|integer|min:1";
        }

        //GESTACION
        $esta_en_gestacion = $this->request->get('esta_en_gestacion');
        if ($esta_en_gestacion) {
            $rules["ultimaMestruacion"] = ["required","date_format:d-m-Y", "ultima_gestacion_limite:".$this->input("ultimaMenstruacion")];
            $rules["control_prenatal"] = ["required","integer", "min:1", "max:15"];
        }

        // *************** RECETA ***************
        $tiene_receta = $this -> request-> get("tiene_receta");
        if ($tiene_receta) {
            $rules["id_farmacia_receta"] = "required|integer|min:1";
            $rules["fecha_expedicion"] = "required|date_format:d-m-Y"; // TODO: Rango de fecha valido para dispensacion?
            $rules["id_producto"] = "required|array|min:1";
        }
        $arrayProductos = $this -> request -> get("id_producto", []);
        if (isset($arrayProductos) && count($arrayProductos) > 0) {
            //dd($arrayProductos);
            foreach($arrayProductos as $key => $val) {
                $rules["id_producto.".$key] = "required|integer|min:10000";
                $rules["nro_stocks.".$key] = "required|integer|min:0";
                $rules["tiempo_periodo.".$key] = "required|integer|min:1";
                $rules["id_via_administracion.".$key] = "required|integer|min:1";
                $rules["posologia.".$key] = "required";
                $rules["nro_med_solictados.".$key] = "required|integer|min:1";
                /* if (($this -> request -> get("nro_stocks")[$key]) > 0) {
                    $rules["nro_med_solictados.".$key] = "required|integer|min:1|max:".$this -> request -> get("nro_stocks")[$key];
                } else {
                    $rules["nro_med_solictados.".$key] = "required|integer|min:0|max:0";
                } */
                $rules["miSelectProd.".$key] = "required|integer|min:1";
            }
            //dd($rules);
        }

        //DIAGNOSTICOS
        $arrayDiagnosticos = $this -> request-> get("nombre_diagnostico", []);
        if (!isset($arrayDiagnosticos) || count($arrayDiagnosticos) == 0) {
            $rules["nombre_diagnostico"] = "required|array|min:1";
        }
        if (isset($arrayDiagnosticos) && count($arrayDiagnosticos) > 0) {
            foreach($arrayDiagnosticos as $key => $val) {
                $rules["nombre_diagnostico.".$key] = "required|string|min:1";
                $rules["id_tipo_diagnostico.".$key] = "required|integer|min:1";
            }
        }

        // ********** PROCEDIMIENTOS **********
        $arrayProcedimientos = $this->request->get('id_procedimiento', []);
        if (count($arrayProcedimientos) <= 0) {
            $rules['nombre_procedimiento'] = 'required|array|min:1';
        }
        if (isset($arrayProcedimientos) && count($arrayProcedimientos) > 0) {
            foreach($arrayProcedimientos as $key => $val) { 
                $rules['nombre_procedimiento.'.$key] = 'required|string|min:1'; 
                // $rules['id_procedimiento.'.$key] = 'required|integer|min:1'; 
                $rules['procedimiento_cantidad.'.$key] = 'required|integer|min:1'; 
                $rules['miSelect.'.$key] = 'required|integer|min:1'; 
            }
        }

         //********** PUERPERA - NEONATOS  **********
         $esta_en_puerperio = strval($this->request->get('esta_en_puerperio', false)) === "true" ? true : false;
         $arrayNeonatos = $this->request->get('numero_documento_puerpera', []);
         if (isset($esta_en_puerperio) && $esta_en_puerperio === true) {
            $rules['esta_en_puerperio'] = ['sometimes', 'boolean', 'puerpera_femenino:'.$this->input('nomsexo'), 'puerpera_edad:'.$this->input('edadpaciente') ];
            $rules['fecha_parto'] = ['required','date_format:d-m-Y', 'fecha_maxima_hoy:'.$this->input('fecha_parto')];
            /* //$rules['hora_parto'] = ['required', 'hora_puerpera:'.$this->input('hora_parto')]; */
            $rules['tipo_parto'] ='required|integer|min:1';
            $rules['complicacion_parto'] = 'required|string|min:0|max:140';
            $rules['descripcion_parto'] = 'sometimes|string|min:0|max:255';
            if (count($arrayNeonatos) <= 0) {
                $rules['numero_documento_puerpera'] = 'required|array|min:1';
            } else {
                foreach($arrayNeonatos as $key => $val) {
                    $rules['estado_vida_puerpera.'.$key] = 'required|string|in:VIVO,MUERTO';
                    $rules['tipo_documento_puerpera.'.$key] = 'required|integer|min:1'; 
                    $idTipoDocIdent = $arrayNeonatos[$key];
                    if (intval($idTipoDocIdent) == 1) {
                        $rules['numero_documento_puerpera.'.$key] = 'required|string|size:8|regex:/^[0-9]{8}$/'; 
                    } else {
                        $rules['numero_documento_puerpera.'.$key] = 'required|string'; 
                    }                
                    $rules['sexo_puerpera.'.$key] = 'required|string|in:M,F'; 
                    $rules['talla_puerpera.'.$key] = 'required|numeric|min:1'; 
                    $rules['peso_puerpera.'.$key] = 'required|numeric|min:1'; 
                }
            }
         }
       
        // ********** INTERCONSULTA **********
        $interconsulta_activo = strval($this->request->get('interconsulta_activo', false)) === "true" ? true : false;
        $arrayInterconsulta = $this->request->get('id_servicio_interconsulta', []);
        if (isset($interconsulta_activo) && $interconsulta_activo === true) {
            if (count($arrayInterconsulta) <= 0) {
                $rules['id_servicio_interconsulta'] = 'required|array|min:1';
            } else {
                foreach($arrayInterconsulta as $key => $val) {
                    // $rules['nombre_servicio_interconsulta.'.$key] = 'required|string|min:1';
                    // $rules['personal_salud.'.$key] = 'required|integer|min:1';            
                    $rules['id_servicio_interconsulta.'.$key] = 'required|integer|min:1'; 
                }
            }
        }

        // ********** EXAMEN DE LABORATORIO **********
        $laboratorio_activo = strval($this->request->get('laboratorio_activo', false)) === "true" ? true : false;
        $arrayExamenLab = $this->request->get('id_examen_lab', []);
        if (isset($laboratorio_activo) && $laboratorio_activo === true) {
            if (count($arrayExamenLab) <= 0) {
                $rules['id_examen_lab'] = 'required|array|min:1';
            } else {
                foreach($arrayExamenLab as $key => $val) {
                    $rules['nombre_examen_lab.'.$key] = 'required|string|min:1'; 
                    // $rules['id_examen_lab.'.$key] = 'required|integer|min:1'; 
                    $rules['examen_lab_observacion.'.$key] = 'sometimes|string|min:1'; 
                    $rules['examen_lab_cantidad.'.$key] = 'required|integer|min:1'; 
                    $rules['miSelectLab.'.$key] = 'required|integer|min:1'; 
                }
            }
        }

        // ********** EXAMEN IMAGENOLOGIA **********
        $imagenologia_activo = strval($this->request->get('imagenologia_activo', false)) === "true" ? true : false;
        $arrayImagenologia = $this->request->get('id_examen_rad', []);
        if (isset($imagenologia_activo) && $imagenologia_activo === true) {
            if (count($arrayImagenologia) <= 0) {
                $rules['id_examen_rad'] = 'required|array|min:1';
            } else {
                foreach($arrayImagenologia as $key => $val) {
                    $rules['nombre_examen_rad.'.$key] = 'required|string|min:1'; 
                    // $rules['id_examen_rad.'.$key] = 'required|integer|min:1'; 
                    /* $rules['id_comparativo.'.$key] = 'required|integer|min:1'; 
                    $rules['id_lado.'.$key] = 'required|integer|min:1'; */ 
                    /* $rules['examen_rad_observacion.'.$key] = 'sometimes|string|min:1';  */
                    $rules['examen_rad_cantidad.'.$key] = 'required|integer|min:1'; 
                    $rules['miSelectRad.'.$key] = 'required|integer|min:1'; 
                }
            }
        }
        //dd($rules);
        return $rules;
    }

    public function messages() {
        $messages = [
            "id_producto.required" => "Debe al menos agregar un producto en la dispensacion de recetas.",
            "id_farmacia_receta.required" => "Debe seleccionar el establecimiento FARMACIA del cual dispensara la receta.",  
            "id_farmacia_receta.integer" => "No debe alterar el sistema, recuerde que todo moviemiento es AUDITADO.",  
            "id_farmacia_receta.min" => "No debe alterar el sistema, recuerde que todo moviemiento es AUDITADO.",  
            "ultimaMestruacion.required" => "El campo ULTIMA MENSTRUACION es requerido.",
            "control_prenatal.required" => "El campo CONTROL PRENATAL es requerido.",
            "control_prenatal.min" => "El valor minimo de CONTROL PRENATAL es 1.",
            "control_prenatal.max" => "El valor maximo de CONTROL PRENATAL es 15",
            "id_cama.required" => "Debe seleccionar el campo CAMA/CAMILLA.",
            "id_cama.min" => "Debe seleccionar el campo CAMA/CAMILLA.",
            "id_area_destino.required" => "No debe alterar el sistema, recuerde que todo moviemiento es AUDITADO.",
            "id_area_destino.min" => "Debe seleccionar el campo AREA DE DESTINO. ",
            'id_prioridad.required'=> 'Debe seleccionar la prioridad del paciente.',
            'id_prioridad.min'=> 'No debe alterar el sistema, recuerde que todo moviemiento es AUDITADO.',
            'id_topico.required'=> 'Debe seleccionar el area origen del paciente.',
            'id_topico.min'=> 'Debe seleccionar el area origen del paciente.',
            "temperatura.required" => "El campo :attribute debe ser completado.",
            "temperatura.numeric" => "El campo :attribute debe ser un numero.",
            "temperatura.min" => "El campo :attribute debe estar entre un rango de 35 y 40.",
            "temperatura.max" => "El campo :attribute debe estar entre un rango de 35 y 40",
            "talla.required" => "El campo :attribute debe ser completado.",
            "talla.numeric" => "El campo :attribute debe ser un numero.",
            "talla.min" => "El campo :attribute debe estar entre un rango de 25 y 250.",
            "talla.max" => "El campo :attribute debe estar entre un rango de 25 y 250.",
            "peso.required" => "El campo :attribute debe ser completado.",
            "peso.numeric" => "El campo :attribute debe ser un numero.",
            "peso.min" => "El campo :attribute debe estar entre un rango de 2 y 250",
            "peso.max" => "El campo :attribute debe estar entre un rango de 2 y 250.",
            "imc.required" => "El campo :attribute debe ser completado.",
            "imc.numeric" => "El campo :attribute debe ser un numero.",
            "peso_x_altura.required" => "El campo :attribute debe ser completado.",
            "peso_x_altura.numeric" => "El campo :attribute debe ser un numero.",
            "saturacion.required" => "El campo :attribute debe ser completado.",
            "saturacion.numeric" => "El campo :attribute debe ser un numero.",
            "saturacion.min" => "El campo :attribute debe estar entre un rango de 60 y 100.",
            "saturacion.max" => "El campo :attribute debe estar entre un rango de 60 y 100.",
            "presion_arterial_x.required" => "El campo :attribute debe ser completado.",
            "presion_arterial_x.numeric" => "El campo :attribute debe ser un numero.",
            "presion_arterial_y.required" => "El campo :attribute debe ser completado.",
            "presion_arterial_y.numeric" => "El campo :attribute debe ser un numero.",
            "frecuencia_respiratoria.required" => "El campo :attribute debe ser completado.",
            "frecuencia_respiratoria.numeric" => "El campo :attribute debe ser un numero.",
            "frecuencia_respiratoria.min" => "El campo :attribute debe estar entre un rango de 10 y 35.",
            "frecuencia_respiratoria.max" => "El campo :attribute debe estar entre un rango de 10 y 35.",
            "frec_cardiaca.required" => "El campo :attribute debe ser completado.",
            "frec_cardiaca.numeric" => "El campo :attribute debe ser un numero.",
            "frec_cardiaca.min" => "El campo :attribute debe estar entre un rango de 50 y 150.",
            "frec_cardiaca.max" => "El campo :attribute debe estar entre un rango de 50 y 150.",
            "oxigenacion.required" => "El campo :attribute debe ser completado.",
            "oxigenacion.numeric" => "El campo :attribute debe ser un numero.",
            "oxigenacion.min" => "El campo :attribute debe estar entre un rango de 21 y 100.",
            "oxigenacion.max" => "El campo :attribute debe estar entre un rango de 21 y 100.",

            // ********* PROCEDIMIENTOS *********
            'nombre_procedimiento.required' => 'Debe ingresar al menos un :attribute.',
            'nombre_procedimiento.array' => 'Debe ingresar al menos un :attribute.',
            'nombre_procedimiento.min' => 'Debe ingresar al menos un :attribute.',
            'nombre_diagnostico.required' => "Debe ingresar al menos un DIAGNOSTICO.",

            // ********* PUERPERA *********
            'numero_documento_puerpera.required' => 'Debe ingresar al menos un :attribute en PUERPERA.',
            'numero_documento_puerpera.array' => 'Debe ingresar al menos un :attribute en PUERPERA.',
            'numero_documento_puerpera.min' => 'Debe ingresar al menos un :attribute en PUERPERA.',
            
            'fecha_parto.required' => 'El campo :attribute es obligatorio en PUERPERA',
            'fecha_parto.date_format' => 'El campo :attribute debe tener el formato Dia-Mes-Año en PUERPERA',

            'hora_parto.required' => 'El campo :attribute es obligatorio en PUERPERA',
            'hora_parto.date_format' => 'El campo :attribute debe tener el formato H:i:s en PUERPERA',

            'tipo_parto.required' => 'El campo :attribute es obligatorio en PUERPERA',
            'tipo_parto.integer' => 'El campo :attribute debe ser un número entero en PUERPERA.',
            'tipo_parto.min' => 'El campo :attribute debe ser como mínimo :min en PUERPERA.',

            'complicacion_parto.required' => 'El campo :attribute es obligatorio en PUERPERA',
            'complicacion_parto.min' => 'El campo :attribute debe tener entre :min y :max caracteres en PUERPERA.',
            'complicacion_parto.max' => 'El campo :attribute debe tener entre :min y :max caracteres en PUERPERA.',

            'descripcion_parto.min' => 'El campo :attribute debe tener entre :min y :max caracteres en PUERPERA.',
            'descripcion_parto.max' => 'El campo :attribute debe tener entre :min y :max caracteres en PUERPERA.',

            // ********* INTERCONSULTA *********
            'id_servicio_interconsulta.required' => 'Debe ingresar al menos una :attribute.',
            'id_servicio_interconsulta.array' => 'Debe ingresar al menos una :attribute.',
            'id_servicio_interconsulta.min' => 'Debe ingresar al menos una :attribute.',

            // ********* EXAMEN DE LABORATORIO *********
            'id_examen_lab.required' => 'Debe ingresar al menos un :attribute.',
            'id_examen_lab.array' => 'Debe ingresar al menos un :attribute.',
            'id_examen_lab.min' => 'Debe ingresar al menos un :attribute.',

            // ********* EXAMEN IMAGENOLOGIA *********
            'id_examen_rad.required' => 'Debe ingresar al menos un :attribute.',
            'id_examen_rad.array' => 'Debe ingresar al menos un :attribute.',
            'id_examen_rad.min' => 'Debe ingresar al menos un :attribute.',
        ];

        // DIAGNOSTICOS (mensajes)
        $arrayDiagnosticos = $this->input('nombre_diagnostico');
        if(isset($arrayDiagnosticos) && count($arrayDiagnosticos) > 0) {
            foreach($arrayDiagnosticos as $key => $value) {
                $messages["nombre_diagnostico.".$key.".required"] = "Debe colocar el nombre del diagnostico ".($key + 1)." en la parte de diagnosticos.";
                $messages["nombre_diagnostico.".$key.".min"] = "El nombre del diagnostico ".($key + 1)." debe tener al menos 1 caracter.";
                $messages["id_tipo_diagnostico.".$key.".required"] = "Debe colocar el tipo de diagnostico ".($key + 1)." en la parte de diagnosticos.";
                $messages["id_tipo_diagnostico.".$key.".integer"] = "El tipo de diagnostico ".($key + 1)." debe ser un numero entero.";
                $messages["id_tipo_diagnostico.".$key.".min"] = "Debe colocar el tipo de diagnostico ".($key + 1)." en la parte de diagnosticos.";
            }
        }  

        // RECETA (mensajes)
        $arrayProductos = $this->input('id_producto');
        if(isset($arrayProductos) && count($arrayProductos) > 0) {
            //dd($arrayProductos);
            foreach($arrayProductos as $key => $value) {
                $messages["id_producto.".$key.".required"] = "Debe seleccionar un nombre de producto valido para el registro de RECETA nro ".($key + 1).".";
                $messages["id_producto.".$key.".integer"] = "Debe seleccionar un nombre de producto valido para el registro de RECETA nro ".($key + 1).".";
                $messages["id_producto.".$key.".min"] = "Debe seleccionar un nombre de producto valido para el registro de RECETA nro ".($key + 1).".";
                $messages["nro_stocks.".$key."required"] = "No borre la informacion del stock del producto a dispensar ".($key + 1).".";
                $messages["nro_med_solictados.".$key.".required"] = "Debe colocar la cantidad de medicamentos del producto a dispensar en el registro de RECETA nro ".($key + 1).".";
                $messages["nro_med_solictados.".$key.".integer"] = "Debe colocar una cantidad valida de medicamentos del producto a dispensar en el registro de RECETA nro ".($key + 1).".";
                $messages["nro_med_solictados.".$key.".min"] = "No puede dispensar una cantidad de medicamentos menor al stock del producto, en el registro de RECETA nro  ".($key + 1).".";
                $messages["nro_med_solictados.".$key.".max"] = "No puede dispensar una cantidad de medicamentos que exceda al stock del producto, en el registro de RECETA nro  ".($key + 1).".";
                $messages["tiempo_periodo.".$key.".required"] = "Digite un tiempo de periodo para los medicamentos dispensados en el registro de RECETA nro ".($key + 1).".";
                $messages["tiempo_periodo.".$key.".integer"] = "Digite un tiempo de periodo valido para los medicamentos dispensados en el registro de RECETA nro ".($key + 1).".";
                $messages["tiempo_periodo.".$key.".min"] = "Digite un tiempo de periodo valido para los medicamentos dispensados en el registro de RECETA nro ".($key + 1).".";
                $messages["id_via_administracion.".$key.".required"] = "Seleccione una via de administracion de medicamento valida para el registro de RECETA nro ".($key + 1).".";
                $messages["id_via_administracion.".$key.".integer"] = "Seleccione una via de administracion de medicamento valida para el registro de RECETA nro ".($key + 1).".";
                $messages["id_via_administracion.".$key.".min"] = "Seleccione una via de administracion de medicamento valida para el registro de RECETA nro ".($key + 1).".";
                $messages["posologia.".$key.".required"] = "Complete el campo POSOLOGIA en el registro de RECETA nro ".($key + 1).".";
                $messages["miSelectProd.".$key.".required"] = "Seleccione un diagnostico valido asociado al registro de RECETA nro ".($key + 1).".";
                $messages["miSelectProd.".$key.".integer"] = "Seleccione un diagnostico valido asociado al registro de RECETA nro ".($key + 1).".";
                $messages["miSelectProd.".$key.".min"] = "Seleccione un diagnostico valido asociado al registro de RECETA nro ".($key + 1)."." ;
            }
        }  

        // *************** PROCEDIMIENTOS ***************
        $arrayProcedimientos = $this->input('id_procedimiento', []);
        if (isset($arrayProcedimientos) && count($arrayProcedimientos) > 0) {
            foreach ($arrayProcedimientos as $key => $value) {
                $nextKey = intval($key) + 1;
                $messages['nombre_procedimiento.'.$key.'.required'] = 'El campo :attribute del procedimiento '.$nextKey.' es obligatorio.';
                $messages['nombre_procedimiento.'.$key.'.min'] =  'El campo :attribute del procedimiento '.$nextKey.' debe tener al menos :min caracteres.';

                /* $messages['id_procedimiento.'.$key.'.required'] = 'El campo :attribute del procedimiento '.$nextKey.' es obligatorio.';
                $messages['id_procedimiento.'.$key.'.integer'] = 'El campo :attribute del procedimiento '.$nextKey.' debe ser un número entero.';
                $messages['id_procedimiento.'.$key.'.min'] = 'El valor minimo campo :attribute del procedimiento '.$nextKey.' es :min'; */

                $messages['procedimiento_cantidad.'.$key.'.required'] = 'El campo :attribute del procedimiento '.$nextKey.' es obligatorio.';
                $messages['procedimiento_cantidad.'.$key.'.integer'] = 'El campo :attribute del procedimiento '.$nextKey.' debe ser un número entero.';
                $messages['procedimiento_cantidad.'.$key.'.min'] = 'El valor minimo campo :attribute del procedimiento '.$nextKey.' es :min';

                $messages['miSelect.'.$key.'.required'] = 'El campo :attribute del procedimiento '.$nextKey.' es obligatorio.';
                $messages['miSelect.'.$key.'.integer'] = 'El campo :attribute del procedimiento '.$nextKey.' debe ser un número entero.';
                $messages['miSelect.'.$key.'.min'] = 'El valor minimo campo :attribute del procedimiento '.$nextKey.' es :min';
            }    
        }

        // *************** PUEPEPERA ***************
        $arrayNeonatos = $this->input('numero_documento_puerpera', []);
        if (isset($arrayNeonatos) && count($arrayNeonatos) > 0) {
            foreach ($arrayNeonatos as $key => $value) {
                $nextKey = intval($key) + 1;
                $messages['estado_vida_puerpera.'.$key.'.required'] = 'El campo :attribute del NEONATO '.$nextKey.' es obligatorio.';
                $messages['estado_vida_puerpera.'.$key.'.in'] =  'El campo :attribute del NEONATO '.$nextKey.' debe ser "VIVO" o "MUERTO.';

                $messages['tipo_documento_puerpera.'.$key.'.required'] = 'El campo :attribute del NEONATO '.$nextKey.' es obligatorio.';
                $messages['tipo_documento_puerpera.'.$key.'.integer'] = 'El campo :attribute del NEONATO '.$nextKey.' debe ser un número entero.';
                $messages['tipo_documento_puerpera.'.$key.'.min'] = 'El valor minimo campo :attribute del NEONATO '.$nextKey.' es :min';

                $idTipoDocIdent = $arrayNeonatos[$key];
                if (intval($idTipoDocIdent) == 1) {
                    $messages['numero_documento_puerpera.'.$key.'.required'] = 'El :attribute del NEONATO '.$nextKey.' es obligatorio.';
                    $messages['numero_documento_puerpera.'.$key.'.size'] = 'El :attribute del NEONATO '.$nextKey.' debe tener exactamente :size caracteres.';
                    $messages['numero_documento_puerpera.'.$key.'.regex'] = 'El :attribute del NEONATO '.$nextKey.' solo debe contener numeros del 0 al 9';
                } else {
                    $messages['numero_documento_puerpera.'.$key.'.required'] = 'El :attribute del NEONATO '.$nextKey.' es obligatorio.';
                }   

                $messages['sexo_puerpera.'.$key.'.required'] = 'El campo :attribute del NEONATO '.$nextKey.' es obligatorio.';
                $messages['sexo_puerpera.'.$key.'.in'] =  'El campo :attribute del NEONATO '.$nextKey.' debe ser "MASCULINO" o "FEMENINO.';

                $messages['talla_puerpera.'.$key.'.required'] = 'El campo :attribute del NEONATO '.$nextKey.' es obligatorio.';
                $messages['talla_puerpera.'.$key.'.numeric'] = 'El campo :attribute del NEONATO '.$nextKey.' debe ser un número.';
                $messages['talla_puerpera.'.$key.'.min'] = 'El valor minimo de la :attribute del NEONATO '.$nextKey.' es :min';

                $messages['peso_puerpera.'.$key.'.required'] = 'El campo :attribute del NEONATO '.$nextKey.' es obligatorio.';
                $messages['peso_puerpera.'.$key.'.numeric'] = 'El campo :attribute del NEONATO '.$nextKey.' debe ser un número entero.';
                $messages['peso_puerpera.'.$key.'.min'] = 'El valor minimo de la :attribute del NEONATO '.$nextKey.' es :min';
            }    
        }

        // *************** INTERCONSULTA ***************
        $arrayInterConsulta = $this->input('id_servicio_interconsulta', []);
        if (isset($arrayInterConsulta) && count($arrayInterConsulta) > 0) {
            foreach ($arrayInterConsulta as $key => $value) {
                $nextKey = intval($key) + 1;
                $messages['id_servicio_interconsulta.'.$key.'.required'] = 'El campo :attribute de la INTERCONSULTA '.$nextKey.' es obligatorio.';
                $messages['id_servicio_interconsulta.'.$key.'.min'] = 'El valor minimo del :attribute de la INTERCONSULTA '.$nextKey.' es :min';

                // $messages['personal_salud.'.$key.'.required'] = 'El campo :attribute de la INTERCONSULTA '.$nextKey.' es obligatorio.';
                // $messages['personal_salud.'.$key.'.integer'] = 'El campo :attribute de la INTERCONSULTA '.$nextKey.' debe ser un número entero.';
                // $messages['personal_salud.'.$key.'.min'] = 'El valor minimo campo :attribute de la INTERCONSULTA '.$nextKey.' es :min';
            }    
        }

        // *************** EXAMEN DE LABORATORIO ***************
        $arrayLaboratorio = $this->input('id_examen_lab', []);
        if (isset($arrayLaboratorio) && count($arrayLaboratorio) > 0) {
            foreach ($arrayLaboratorio as $key => $value) {
                $nextKey = intval($key) + 1;
                $messages['nombre_examen_lab.'.$key.'.required'] = 'El campo :attribute del EXAMEN DE LABORATORIO '.$nextKey.' es obligatorio.';
                $messages['nombre_examen_lab.'.$key.'.min'] = 'El valor minimo del :attribute de la EXAMEN DE LABORATORIO '.$nextKey.' es :min';

                $messages['examen_lab_cantidad.'.$key.'.required'] = 'El campo :attribute del EXAMEN DE LABORATORIO '.$nextKey.' es obligatorio.';
                $messages['examen_lab_cantidad.'.$key.'.integer'] = 'El campo :attribute del EXAMEN DE LABORATORIO '.$nextKey.' debe ser un número entero.';
                $messages['examen_lab_cantidad.'.$key.'.min'] = 'El valor minimo campo :attribute del EXAMEN DE LABORATORIO '.$nextKey.' es :min';

                $messages['miSelectLab.'.$key.'.required'] = 'El campo :attribute del EXAMEN DE LABORATORIO '.$nextKey.' es obligatorio.';
                $messages['miSelectLab.'.$key.'.integer'] = 'El campo :attribute del EXAMEN DE LABORATORIO '.$nextKey.' debe ser un número entero.';
                $messages['miSelectLab.'.$key.'.min'] = 'El valor minimo campo :attribute del EXAMEN DE LABORATORIO '.$nextKey.' es :min';
            }    
        }

        // *************** EXAMEN IMAGENOLOGIA ***************
        $arrayImagenologia = $this->input('id_examen_rad', []);
        if (isset($arrayImagenologia) && count($arrayImagenologia) > 0) {
            foreach ($arrayImagenologia as $key => $value) {
                $nextKey = intval($key) + 1;
                $messages['nombre_examen_rad.'.$key.'.required'] = 'El campo :attribute del EXAMEN IMAGENOLOGIA '.$nextKey.' es obligatorio.';
                $messages['nombre_examen_rad.'.$key.'.min'] = 'El valor minimo del :attribute de la EXAMEN IMAGENOLOGIA '.$nextKey.' es :min';

                $messages['id_comparativo.'.$key.'.required'] = 'El campo :attribute del EXAMEN IMAGENOLOGIA '.$nextKey.' es obligatorio.';
                $messages['id_comparativo.'.$key.'.integer'] = 'El campo :attribute del EXAMEN IMAGENOLOGIA '.$nextKey.' debe ser un número entero.';
                $messages['id_comparativo.'.$key.'.min'] = 'El valor minimo campo :attribute del EXAMEN IMAGENOLOGIA '.$nextKey.' es :min';

                $messages['id_lado.'.$key.'.required'] = 'El campo :attribute del EXAMEN IMAGENOLOGIA '.$nextKey.' es obligatorio.';
                $messages['id_lado.'.$key.'.integer'] = 'El campo :attribute del EXAMEN IMAGENOLOGIA '.$nextKey.' debe ser un número entero.';
                $messages['id_lado.'.$key.'.min'] = 'El valor minimo campo :attribute del EXAMEN IMAGENOLOGIA '.$nextKey.' es :min';

                $messages['examen_rad_cantidad.'.$key.'.required'] = 'El campo :attribute del EXAMEN IMAGENOLOGIA '.$nextKey.' es obligatorio.';
                $messages['examen_rad_cantidad.'.$key.'.integer'] = 'El campo :attribute del EXAMEN IMAGENOLOGIA '.$nextKey.' debe ser un número entero.';
                $messages['examen_rad_cantidad.'.$key.'.min'] = 'El valor minimo campo :attribute del EXAMEN IMAGENOLOGIA '.$nextKey.' es :min';

                $messages['miSelectRad.'.$key.'.required'] = 'El campo :attribute del EXAMEN IMAGENOLOGIA '.$nextKey.' es obligatorio.';
                $messages['miSelectRad.'.$key.'.integer'] = 'El campo :attribute del EXAMEN IMAGENOLOGIA '.$nextKey.' debe ser un número entero.';
                $messages['miSelectRad.'.$key.'.min'] = 'El valor minimo campo :attribute del EXAMEN IMAGENOLOGIA '.$nextKey.' es :min';
            }    
        }
        //dd($messages);
        return $messages;
    }
}
