<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AdmisionFormRequest extends Request
{

    public function all()
    {
        $data = parent::all();
        //dd($data);
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

    public function rules()
    {
        $tipodoc_conducido = "";
        $dni_conducido_buscar = "";
        $telfconducido = "";
        if ($this -> input("id_tipo_parentesco") != "" && $this -> input("id_tipo_parentesco") != "8") {
            $tipodoc_conducido = "required|integer|min:1";
            $telfconducido = "required|string|min:9|max:9";       
            $dni_conducido_buscar = "required|string";
            if ($this -> input("tipodoc_conducido") == 1) {
                $dni_conducido_buscar = "required|string|min:8|max:8";
            }
        }
        return [

            //TODO: Analizar patron del codigo SITEDS.
            "cod_siteds_susalud" => "string|max:12",

            "id_situacion" => "required|integer|min:1",
            "telefono" => "string|min:9|max:9",
            "Departamento" => "required|integer|min:1",
            "Provincia" => "required|integer|min:1",
            "Distrito" => "required|integer|min:1",
            "direccion_referencial" => "",
            "id_tipo_parentesco"=> "required|integer|min:1",
            "tipodoc_conducido" => $tipodoc_conducido,
            "telfconducido" => $telfconducido,
            "dni_conducido_buscar" => $dni_conducido_buscar,
        ];
    }

    public function messages()
    {
        return [
            "cod_siteds_susalud.required" => "Debe ingresar el codigo del SITEDS.",
            "cod_siteds_susalud.max" => "El codigo SITEDS debe tener un maximo de 12 numeros.",
            "id_situacion.required" => "Debe seleccionar la opcion de SITUACION del paciente.",
            "id_situacion.min" => "No debe alterar el sistema, recuerde que todo moviemiento es AUDITADO.",
            "telefono.required"=> "Debe colocar el numero de telefono.",
            "telefono.min"=> "El numero de telefono debe tener 9 caracteres.",
            "telefono.max"=> "El numero de telefono debe tener 9 caracteres.",
            "Departamento.required" => "Debe seleccionar el departamento origen del paciente.",
            "Departamento.min" => "No debe alterar el sistema, recuerde que todo moviemiento es AUDITADO.",
            "Provincia.required"=> "Debe seleccionar la provincia origen del paciente.",
            "Provincia.min"=> "No debe alterar el sistema, recuerde que todo moviemiento es AUDITADO.",
            "Distrito.required"=> "Debe seleccionar el distrito origen del paciente.",
            "Distrito.min"=> "No debe alterar el sistema, recuerde que todo moviemiento es AUDITADO.",
            "direccion_referencial.required"=> "Debe ingresar la direccion referencial del paciente.",
            "id_tipo_parentesco.required" => "Debe seleccionar la opcion de 'CONDUCIDO POR.'",
            "id_tipo_parentesco.min" => "No debe alterar el sistema, recuerde que todo moviemiento es AUDITADO.",
            "tipodoc_conducido.required" => "Debe seleccionar el tipo de documento de la persona que conduce al paciente.",
            "tipodoc_conducido.min" => "No debe alterar el sistema, recuerde que todo moviemiento es AUDITADO.",
            "telfconducido.required" => "Debe colocar el numero de telefono de la persona que conduce al paciente.",
            "telfconducido.min" => "Ingrese un numero de telefono valido en el campo 'Telefono' de la persona que conduce al paciente.",
            "telfconducido.max" => "Ingrese un numero de telefono valido en el campo 'Telefono' de la persona que conduce al paciente.",
            "dni_conducido_buscar.required" => "Debe ingresar el doc de identidad de la persona que conduce al paciente.",
            "dni_conducido_buscar.min" => "Ingrese un numero de DNI valido de la persona que conduce al paciente.",
            "dni_conducido_buscar.max" => "Ingrese un numero de DNI valido de la persona que conduce al paciente.",
        ];
    }
}
