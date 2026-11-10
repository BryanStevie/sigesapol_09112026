<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AltaFormRequest extends Request
{
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
        $rules = [
            "fecha_egreso" => ["required","date_format:d-m-Y", "fecha_egreso_limite:".$this->input("fecha_egreso")],
            //"hora_egreso" => ["required", "hora_egreso_limite:".$this->input("hora_egreso")],
            "id_tipo_egreso" => ["required","integer","min:1", "tipos_validos:".$this->input("id_tipo_egreso")],
        ];

        error_log($this->input("hora_egreso"));

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

        // TIPOS DE EGRESO (en caso de agregar mas tipos, agregar en este switch)
        $tiene_tipo_egreso = ($this -> request -> get("id_tipo_egreso")) != null ? true : false;
        if($tiene_tipo_egreso) {
            $tipo_egreso = $this -> request -> get("id_tipo_egreso");
            switch (intval($tipo_egreso)) {
                case 1:
                    $rules["id_condicion_alta"] = "required|integer|min:1";
                    break;
                case 2:
                    // TODO: preguntar si este campo es importante en los tipos de egreso que no sea tipo DOMICILIARIO (para hospitalizacion si es importante creo)
                    //$rules["id_condicion_alta"] = "required|integer|min:1";
                    $rules["id_servicio_hospitalizacion"] = ["required","servicio_valido:".$this -> input("id_servicio_hospitalizacion")];
                    break;
                case 3:
                    $rules["id_establecimiento_traslado"] = ["required","integer"];
                    $rules["obs_traslado"] = ["required"];
                    $rules["especialida_destino"] = ["required"];
                    $rules["especialidad_destino_otro"] = ["required"];
                    $rules["condicion_traslado"] = ["required"];
                    break;
                case 4:
                    $rules["necropsia"] = ["required"];
                    $rules["causas_muerte"] = ["required"];
                    $rules["fecha_defuncion"] = ["required", "fecha_defuncion_valida:".$this -> input("fecha_defuncion")];
                    //$rules["hora_defuncion"] = ["required", "hora_defuncion_valida:".$this -> input("hora_defuncion")];
                    break;
                case 6:
                    $rules["obs_fuga"] = ["required"];
                    break;
                case 7:
                    $rules["obs_retiro"] = ["required"];
                    break;
                default:
                    break;
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            // ********* DIAGNÓSTICOS *********
            'nombre_diagnostico.required' => 'Debe ingresar al menos un DIAGNOSITCO.',
            'nombre_diagnostico.array' => 'Debe ingresar al menos un DIAGNOSTICO.',
            'nombre_diagnostico.min' => 'Debe ingresar al menos un DIAGNOSITCO.',
            "id_tipo_egreso.required" => "Debe seleccionar un TIPO DE EGRESO valido.",
            "id_tipo_egreso.integer" => "Debe seleccionar un TIPO DE EGRESO valido.",
            "id_tipo_egreso.min" => "Debe seleccionar un TIPO DE EGRESO valido.",
            // TIPO DE EGRESOS
            "id_condicion_alta.required" => "Debe seleccionar una CONDICION DE ALTA valida.",
        ];
        // DIAGNOSTICOS (mensajes)
        $arrayDiagnosticos = $this->input('nombre_diagnostico');
        if(isset($arrayDiagnosticos) && count($arrayDiagnosticos) > 0) {
            foreach($arrayDiagnosticos as $key => $value) {
                $messages["nombre_diagnostico.".$key.".required"] = "Debe colocar el NOMBRE DE DIAGNOSTICO ".($key + 1)." en la parte de diagnosticos.";
                $messages["nombre_diagnostico.".$key.".min"] = "El NOMBRE DE DIAGNOSTICO ".($key + 1)." debe tener al menos 1 caracter.";
                $messages["id_tipo_diagnostico.".$key.".required"] = "Debe colocar el TIPO DE DIAGNOSTICO ".($key + 1)." en la parte de diagnosticos.";
                $messages["id_tipo_diagnostico.".$key.".integer"] = "El TIPO DE DIAGNOSTICO ".($key + 1)." debe ser un numero entero.";
                $messages["id_tipo_diagnostico.".$key.".min"] = "Debe colocar el TIPO DE DIAGNOSTICO ".($key + 1)." en la parte de diagnosticos.";
            }
        } 
        // TODO: mensajes para la parte de tipo de egresos

        return $messages;
    }
}
