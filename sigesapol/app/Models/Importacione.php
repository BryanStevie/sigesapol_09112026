<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Importacione extends Model
{
    //
    public function ShowAllByIdEstablecimiento($id_establecimiento,$tabla) {
        $datos=DB::table('importaciones')
                ->select('importaciones.*')
                ->where('importaciones.id_establecimiento',$id_establecimiento)
                ->where('importaciones.tabla',$tabla)
                ->orderBy('created_at','desc')
                ->get();
        return $datos;
    }
    
	public function ShowAllByIdFarmacia($id_farmacia,$tabla) {
        $datos=DB::table('importaciones')
                ->select('importaciones.*')
                ->where('importaciones.id_farmacia',$id_farmacia)
                ->where('importaciones.tabla',$tabla)
                ->orderBy('created_at','desc')
                ->get();
        return $datos;
    }
	
    public function GetValidByIdFarmaciaPendiente($id){
        $datos=DB::table('importaciones') 
                ->select('id','id_establecimiento')
                ->where('id_farmacia','=',$id)
                ->where('estado','=',1)
                ->first();
        return $datos;       
    }
	
	public function GetValidByIdFarmaciaAndEstado($id,$estado){
        $datos=DB::table('importaciones') 
                ->select('id','id_establecimiento')
                ->where('id_farmacia','=',$id)
                ->where('estado','=',$estado)
                ->first();
        return $datos;       
    }
	
	
}
