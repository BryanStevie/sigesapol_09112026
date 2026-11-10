<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Concepto extends Model {

    function getConcepto($opcion) {
        $cad = "Select id,descripcion_conceptos From conceptos Where opcion!='".$opcion."' and estado=1 Order By id";
        $data = DB::select($cad);
        return $data;
    }

	function getConceptoTodos() {
        $cad = "Select * From conceptos Order By id";
        $data = DB::select($cad);
        return $data;
    }
	
    public function cp_yaregistrada($dato){
        $datos=DB::table('conceptos') 
                ->where('conceptos.descripcion_conceptos','=',$dato)
                ->select('conceptos.*')
                ->first();
        return $datos;                      
    }

	
}