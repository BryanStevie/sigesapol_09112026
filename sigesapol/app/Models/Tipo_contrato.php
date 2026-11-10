<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Tipo_contrato extends Model
{
    //
    public function ShowAll(){
		/*
        $datos=DB::table('tipo_contratos') 
                ->where('tipo_contratos.estado','=',1)
                ->select('tipo_contratos.*')                
                ->get();
        return $datos;
		*/
		
		$cad = "select tipo_contratos.*
from tipo_contratos";
		$cad .= " Where COALESCE(tipo_contratos.estado,'1')!='3' ";
		$data = DB::select($cad);
        return $data;
		
    }
    
    public function ExisteTipoContrato($nombre, $id=0) {
        $data = Tipo_contrato::where(DB::raw('upper(nombre)'), '=', trim(strtoupper($nombre)))->where('estado', '=', '1')->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }    
	
}
