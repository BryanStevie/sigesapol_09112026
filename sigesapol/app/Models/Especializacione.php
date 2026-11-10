<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Especializacione extends Model
{
    //
    public function ShowAll(){
		/*
        $datos=DB::table('especializaciones') 
                ->where('especializaciones.estado','=',1)
                ->select('especializaciones.*')                
                ->get();
        return $datos;
		*/
		$cad = "select especializaciones.*
from especializaciones";
		$cad .= " Where COALESCE(especializaciones.estado,1)!=3 ";
		$data = DB::select($cad);
        return $data;
    }
    
    public function ShowAll_v2(){
      $cad = "select especializaciones.* from especializaciones";
      $cad .= " Where COALESCE(especializaciones.estado,1)!=3 ";
      $cad .= " order by nombre asc";
      $data = DB::select($cad);
      return $data;
    }

    public function ObtenerEspcialidadDelProfesional($id_medico) {
        $datos=DB::table('medicos') 
                ->join('especializaciones','medicos.id_especializacion','=','especializaciones.id')
                ->where('medicos.id','=',$id_medico)
                ->select('especializaciones.*','medicos.nombre as nombre_medico','medicos.paterno','medicos.materno')                
                ->first();
        return $datos;
    }
	
	public function ExisteEspecialidad($nombre, $id=0) {
        $data = Especializacione::where(DB::raw('upper(nombre)'), '=', trim(strtoupper($nombre)))->where('estado', '=', '1')->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }    
	
}
