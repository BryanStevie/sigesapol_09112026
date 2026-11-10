<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Tipo_procedimiento extends Model
{
    //
    public function ShowAll(){
		$cad = "select tipo_procedimientos.*
from tipo_procedimientos";
		$cad .= " Where COALESCE(tipo_procedimientos.estado,'1')!='3' ";
		$data = DB::select($cad);
        return $data;
		
    }
    
    public function ExisteTipoProcedimiento($nombre, $id=0) {
        $data = Tipo_procedimiento::where(DB::raw('upper(descripcion_tipo_procedimiento)'), '=', trim(strtoupper($nombre)))->where('estado', '=', '1')->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }    
	
}
