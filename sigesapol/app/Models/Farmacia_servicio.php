<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Farmacia_servicio extends Model
{
    //
	public function GetServiciosByFarmacia($id_farmacia) {
        $datos=DB::table('farmacia_servicios')
                ->join('consultorios','farmacia_servicios.id_servicio','=','consultorios.id')
                ->select('consultorios.*')   
                ->where('farmacia_servicios.id_farmacia',$id_farmacia)
                ->orderby('consultorios.nombre','asc')
                ->get();
		//var_dump($datos);
        return $datos;
    }
	
}
