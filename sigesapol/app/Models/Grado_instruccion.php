<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Grado_instruccion extends Model
{
    protected $table = 'Grado_instrucciones';
  
    public function ShowGradoInstruccion()
    {
        $cad = "select id, nombre from grado_instrucciones where estado='1' order by nombre asc";
        $data = DB::select($cad);
        return $data;
    }
    
}
