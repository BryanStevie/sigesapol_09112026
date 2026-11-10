<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use SoapClient;
use Carbon\Carbon;

class Antecedente extends Model
{
  
    public $table = 'antecedentes';
    
    protected $dates = ['deleted_at'];

    public $fillable = [
        'descripcion',
        'paciente_id',
        'alergia_medicamentos',
        'alergia_alimentos',
        'alergia_picadura_insectos',
        'vacuna_antitetano_dosis',
        'vacuna_hepatitis_dosis',
        'vacuna_influenza_dosis'
    ];

    protected $casts = [
        'descripcion' => 'string',
        'paciente_id' => 'integer'
    ];

    public static $rules = [
        
    ];

    function getAntecedentes($id){
        $cad = "select a.* 
                from antecedentes a
                where  a.paciente_id = '".$id."' ";
        $data = DB::select($cad);
        
        return $data;
    }

    function getAntecedentePacientes($id){
        $cad = "select a.* 
                from paciente_tipo_antecedente a
                where  a.paciente_id = '".$id."' ";
        $data = DB::select($cad);
        
        return $data;
    } 

  
}
