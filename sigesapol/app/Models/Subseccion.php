<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Subseccion extends Model {

    public function ShowSubseccion($seccion){
    	$cad = "select id, codigo, nombre

 				from sub_seccion_procedimientos where id_seccion = '$seccion' Order by id asc";
        $data = DB::select($cad);
        return $data;
    }

    public function BuscaSubSeccion($id){
    	
        $datos=DB::table('sub_seccion_procedimientos') 
                ->select('sub_seccion_procedimientos.*')
                ->where('sub_seccion_procedimientos.id',$id)
                ->first();
        return $datos;
    }

    public function ShowSubseccionCodigo($seccion){
    	$data = DB::table('sub_seccion_procedimientos')
                ->select('sub_seccion_procedimientos.*')
                ->join('seccion_procedimientos', 'sub_seccion_procedimientos.id_seccion', '=', 'seccion_procedimientos.id')
                ->where('seccion_procedimientos.codigo', $seccion)
                                
                ->get();
        
        return $data;
    }
}