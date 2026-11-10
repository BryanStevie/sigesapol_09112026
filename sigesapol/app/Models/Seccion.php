<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Seccion extends Model {

    public function ShowSeccion($grupo){
    	$cad = "select id, codigo, nombre
 				from seccion_procedimientos where id_grupo = $grupo Order by id asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowSeccionCodigo($grupo){
    	$cad = "select id, codigo, nombre
 				from seccion_procedimientos where codigo = '$grupo' Order by id asc";
        $data = DB::select($cad);
        return $data;
    }

    public function BuscaSeccion($id){
    	$datos=DB::table('seccion_procedimientos') 
                ->select('seccion_procedimientos.*')
                ->where('seccion_procedimientos.id',$id)
                ->first();
        return $datos;
    }
}