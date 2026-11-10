<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tipo_grado extends Model
{
    public function ShowTipoGrado(){
    	$cad = "select id, descripcion from tipo_grados where estado='1' Order By descripcion asc";
        $data = DB::select($cad);
        return $data;
    }
    
}