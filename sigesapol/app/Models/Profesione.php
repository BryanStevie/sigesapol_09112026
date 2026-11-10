<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Profesione extends Model
{
    //
    public function ShowAll(){
		/*
        $datos=DB::table('profesiones') 
                ->where('profesiones.estado','=',1)
                ->select('profesiones.*')                
                ->get();
        return $datos;
		*/
		$cad = "select profesiones.*
from profesiones";
		$cad .= " Where COALESCE(profesiones.estado,'1')!='3' ";
		$data = DB::select($cad);
        return $data;
		
    }

    public function ShowAll_v2(){
      $cad = "select profesiones.* from profesiones";
      $cad .= " Where COALESCE(profesiones.estado,'1')!='3' ";
      $cad .= " order by nombre asc";
      $data = DB::select($cad);
      return $data;
    }
    
	public function ExisteProfesion($nombre, $id=0) {
        $data = Profesione::where(DB::raw('upper(nombre)'), '=', trim(strtoupper($nombre)))->where('estado', '=', '1')->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }    
	
}
