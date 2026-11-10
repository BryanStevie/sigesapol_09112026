<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Origen_paciente extends Model
{
    //
    public function ShowAll(){
		/*
        $datos=DB::table('origen_pacientes') 
                ->where('origen_pacientes.estado','=',1)
                ->select('origen_pacientes.*')                
                ->get();
        return $datos;
		*/
		$cad = "select origen_pacientes.*
from origen_pacientes";
		$cad .= " Where COALESCE(origen_pacientes.estado,'1')!='3' ";
		$data = DB::select($cad);
        return $data;
		
    }
    
	public function ExisteOrigenPaciente($nombre, $id=0) {
        $data = Origen_paciente::where(DB::raw('upper(nombre)'), '=', trim(strtoupper($nombre)))->where('estado', '=', '1')->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }    
	
}
