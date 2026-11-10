<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use SoapClient;
use Carbon\Carbon;

class Tipo_antecedente extends Model
{
  
    public $table = 'tipo_antecedentes';
    
    protected $dates = ['deleted_at'];

    public $fillable = [
        'descripcion',
        'paciente_id'
    ];


    protected $casts = [
        'descripcion' => 'string',
        'paciente_id' => 'integer'
    ];

    public static $rules = [
        
    ];
 
    function getTipoAntecedentes($id){
        $cad = "select a.* 
                from tipo_antecedentes a
                where  a.tipo_antecedente = '".$id."'
                order by id asc ";
        $data = DB::select($cad);
        
        return $data;
    }
  
}
