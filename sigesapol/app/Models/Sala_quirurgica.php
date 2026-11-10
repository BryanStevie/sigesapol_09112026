<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Sala_quirurgica extends Model
{

    public function ShowallById_Ipress($id_ipress){
		
		$cad = "select *
from sala_quirurgicas
where sala_quirurgicas.id_establecimiento=".$id_ipress;
		$cad .= " And COALESCE(sala_quirurgicas.estado,'1')!='3' ";
        $data = DB::select($cad);
        return $data;
		
    }

	public function ExisteSalaQuirurgica($nombre, $id_establecimiento, $id=0) {
        $data = Sala_quirurgica::where(DB::raw('upper(nombre)'), '=', trim(strtoupper($nombre)))->where('id_establecimiento', '=', $id_establecimiento)->where('estado', '=', '1')->OrWhereNull('estado')->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }    
    
}
