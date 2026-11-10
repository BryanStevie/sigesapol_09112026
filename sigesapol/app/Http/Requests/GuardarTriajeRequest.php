<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Carbon\Carbon;

class GuardarTriajeRequest extends Request
{
    /**
     * Get all of the input and files for the request.
     *
     * @return array
     */
    public function all()
    {
        $data = parent::all();
        if (isset($data['esta_gestando'])) {
            $data['esta_gestando'] = $data['esta_gestando'] === 'true' ? true : false;
        }
        if (isset($data['esta_en_puerperio'])) {
            $data['esta_en_puerperio'] = $data['esta_en_puerperio'] === 'true' ? true : false;
        }
        return $data;
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

    public function attributes()
    {
        return [
            'idTipoDocIdent' => 'TIPO DOCUMENTO',
            'dni_beneficiario' => 'NRO DOCUMENTO',
            'fecha_nac' => 'FECHA NACIMIENTO',
            'edad_pac' => 'EDAD',
            'sexo' => 'SEXO',
            'nombre' => 'NOMBRES',
            'paterno' => 'APELLIDO PATERNO',
            'materno' => 'APELIDO MATERNO',
            'id_forma_ingreso' => 'FORMA DE INGRESO',
            'id_estado_ingreso' => 'ESTADO DE INGRESO',
            'id_motivo_ingreso' => 'MOTIVO DE INGRESO',
            'id_prioridad' => 'PRIORIDAD',
            'id_topico' => 'AREA/SERVICIO',
            'observacion' => 'MOTIVO DE LA CONSULTA',
            'presion_arterial_x' => 'PRESIÓN ARTERIAL',
            'presion_arterial_y' => 'PRESIÓN ARTERIAL',
            'frec_cardiaca' => 'FREC. CARDIACA',
            'frecuencia_respiratoria' => 'FREC. RESPIRATORIA',
            'oxigenacion' => 'Fi02',
            'saturacion' => 'Sa02',
            'temperatura' => 'TEMPERATURA',
            'talla' => 'TALLA',
            'peso' => 'PESO',
            'imc' => 'IMC',
            'peso_x_altura' => 'PESO IDEAL',

            'esta_gestando' => 'GESTACIÓN',
            'ultimaMestruacion' => 'ULTIMA MESTRUACIÓN',
            'parto_probable' => 'FECHA PROBABLE DE PARTO',
            'semana_gesta' => 'SEMANAS DE GESTACIÓN',
            'control_prenatal' => 'N° DE CONTROLES PRENATALES',
            'duda' => 'DUDA',

            'esta_en_puerperio' => 'PUERPERA',
            'fecha_parto' => 'FECHA PARTO',
            'hora_parto' => 'HORA PARTO',
            'tipo_parto' => 'TIPO DE PARTO',
            'complicacion_parto' => 'COMPLICACIÓN',
            'descripcion_parto' => 'DESCRIPCIÓN',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $idTipoDocIdent = $this->input('id_tipo_doc_ident');
        $dniRules = '';
        if ($idTipoDocIdent == 1) {
            $dniRules = 'required|string|regex:/^[0-9]{8}$/';
        } else {
            $dniRules = 'required|string';
        }

        $rules = [
            'id_tipo_doc_ident' => 'required|integer|min:1|max:11',
            'dni_beneficiario' => $dniRules,

            'fecha_nac' => 'required|date_format:d/m/Y|fecha_maxima_hoy_dd/mm/yyyy',
            'edad_pac' => 'required|integer|between:0,150',
            'sexo' => 'required|string|in:F,M',

            'nombre' => "required|string",
            'paterno' => "required|string",
            //'materno' => "required|string",
            /* 'nombre' => "required|string|regex:/^[0-9a-zA-ZÀ-ÖØ-öø-ÿĀ-žĄ-ŽÁÉÍÓÚÝÀÈÌÒÙÁÉÍÓÚÝÀÈÌÒÙÂÊÎÔÛÃÕÄËÏÖÜŸŃÑáéíóúýàèìòùâêîôûãõäëïöüÿńñ\s]+$/", */
            /* 'paterno' => "required|string|regex:/^[0-9a-zA-ZÀ-ÖØ-öø-ÿĀ-žĄ-ŽÁÉÍÓÚÝÀÈÌÒÙÁÉÍÓÚÝÀÈÌÒÙÂÊÎÔÛÃÕÄËÏÖÜŸŃÑáéíóúýàèìòùâêîôûãõäëïöüÿńñ\s]+$/", */
            /* 'materno' => "required|string|regex:/^[0-9a-zA-ZÀ-ÖØ-öø-ÿĀ-žĄ-ŽÁÉÍÓÚÝÀÈÌÒÙÁÉÍÓÚÝÀÈÌÒÙÂÊÎÔÛÃÕÄËÏÖÜŸŃÑáéíóúýàèìòùâêîôûãõäëïöüÿńñ\s]+$/", */

            'id_forma_ingreso' => 'required|integer|min:1',
            'id_estado_ingreso' => 'required|integer|min:1',
            'id_motivo_ingreso' => 'required|integer|min:1',
            'id_prioridad' => 'required|integer|min:1',
            'id_topico' => 'required|integer|min:1',
            'observacion' => 'sometimes|string|min:1|max:255',

            /* 'presion_arterial_x' => 'sometimes|numeric|between:0,250',
            'presion_arterial_y' => 'sometimes|numeric|between:0,140',
            'frec_cardiaca' => 'sometimes|numeric|between:50,150',
            'frecuencia_respiratoria' => 'sometimes|numeric|between:10,35',
            'oxigenacion' => 'sometimes|numeric|between:21,100',
            'saturacion' => 'sometimes|numeric|between:60,100',
            'temperatura' => 'sometimes|numeric|between:35,40',
            'talla' => 'sometimes|numeric|between:30,200',
            'peso' => 'sometimes|numeric|between:1,180',
            'imc' => 'sometimes|numeric|between:1,180',
            'peso_x_altura' => 'sometimes|numeric|between:1,180', */

            'esta_gestando' => ['sometimes', 'boolean'],
            'esta_en_puerperio' => ['sometimes', 'boolean'],
        ];

        $esta_gestando = strval($this->request->get('esta_gestando', false)) === "true" ? true : false;
        if (isset($esta_gestando) && $esta_gestando === true) {
           $rules['esta_gestando'] = ['sometimes', 'boolean', 'gestacion_femenino:'.$this->input('sexo'), 'gestacion_edad:'.$this->input('edad_pac') ];

           $rules['ultimaMestruacion'] = 'required|date_format:d-m-Y';
           $rules['parto_probable'] = 'required|date_format:d-m-Y';
           $rules['semana_gesta'] ='required|string|min:10|max:15';
           $rules['control_prenatal'] = 'required|integer|between:1,100';
           $rules['duda'] = 'required|in:1,0';
        }

        $esta_en_puerperio = strval($this->request->get('esta_en_puerperio', false)) === "true" ? true : false;
        if (isset($esta_en_puerperio) && $esta_en_puerperio === true) {
           $rules['esta_en_puerperio'] = ['sometimes', 'boolean', 'puerpera_femenino:'.$this->input('sexo'), 'puerpera_edad:'.$this->input('edad_pac') ];

        //    $rules['fecha_parto'] = 'required|date_format:d-m-Y|fecha_maxima_hoy';
           $rules['hora_parto'] = 'required|date_format:H:i';
           $rules['tipo_parto'] ='required|integer|min:1';
           $rules['complicacion_parto'] = 'required|string|min:0|max:140';
           $rules['descripcion_parto'] = 'sometimes|string|min:0|max:255';
        }

        //dd($rules);
        return $rules;
    }

    public function messages()
    {
        return [
            'dni_beneficiario.regex' => 'Ingrese un DNI valido.',
            'presion_arterial_x.between' => 'El campo :attribute tiene que estar entre :min y :max',
            'presion_arterial_y.between' => 'El campo :attribute tiene que estar entre :min y :max',
            'frec_cardiaca.between' => 'El campo :attribute tiene que estar entre :min y :max',
            'frecuencia_respiratoria.between' => 'El campo :attribute tiene que estar entre :min y :max',
            'oxigenacion.between' => 'El campo :attribute tiene que estar entre :min y :max',
            'saturacion.between' => 'El campo :attribute tiene que estar entre :min y :max',
            'temperatura.between' => 'El campo :attribute tiene que estar entre :min y :max',
            'talla.between' => 'El campo :attribute tiene que estar entre :min y :max',
            'peso.between' => 'El campo :attribute tiene que estar entre :min y :max',
            'imc.between' => 'El campo :attribute tiene que estar entre :min y :max',
            'peso_x_altura.between' => 'El campo :attribute tiene que estar entre :min y :max'
        ];
    }
}