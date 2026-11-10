<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DetalleAtencionFormRequest extends Request
{
    public function all() {
        $data = parent::all();
        $data["interconsulta_activo"] = $data["interconsulta_activo"] === "true" ? true : false; // --> INTERCONSULTA
        $data["laboratorio_activo"] = $data["laboratorio_activo"] === "true" ? true : false; // --> LABORATORIO
        $data["imagenologia_activo"] = $data["imagenologia_activo"] === "true" ? true : false; // --> IMAGENOLOGIA
        $data["tiene_receta"] = $data["tiene_receta"] === "true" ? true : false; // --> RECETA
        return $data;
    }

    public function attributes()
    {
        $attributes = [
            "id_servicio_interconsulta" => "INTERCONSULTA", // INTERCONSULTA
            "id_examen_lab" => "EXAMEN DE LABORATORIO", // LABORATORIO
            "id_examen_rad" => "EXAMEN IMAGENOLOGIA", // IMAGENOLOGIA
        ];

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
        $rules = [];
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
                $rules["miSelectProd.".$key] = "required|integer|min:1";
            }
            //dd($rules);
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
                    $rules['id_comparativo.'.$key] = 'required|integer|min:1'; 
                    $rules['id_lado.'.$key] = 'required|integer|min:1'; 
                    $rules['examen_rad_observacion.'.$key] = 'sometimes|string|min:1'; 
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

        // *************** INTERCONSULTA ***************
        $arrayInterConsulta = $this->input('id_servicio_interconsulta', []);
        if (isset($arrayInterConsulta) && count($arrayInterConsulta) > 0) {
            foreach ($arrayInterConsulta as $key => $value) {
                $nextKey = intval($key) + 1;
                $messages['id_servicio_interconsulta.'.$key.'.required'] = 'El campo :attribute de la INTERCONSULTA '.$nextKey.' es obligatorio.';
                $messages['id_servicio_interconsulta.'.$key.'.min'] = 'El valor minimo del :attribute de la INTERCONSULTA '.$nextKey.' es :min';

                /*$messages['personal_salud.'.$key.'.required'] = 'El campo :attribute de la INTERCONSULTA '.$nextKey.' es obligatorio.';
                $messages['personal_salud.'.$key.'.integer'] = 'El campo :attribute de la INTERCONSULTA '.$nextKey.' debe ser un número entero.';
                $messages['personal_salud.'.$key.'.min'] = 'El valor minimo campo :attribute de la INTERCONSULTA '.$nextKey.' es :min';*/
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
