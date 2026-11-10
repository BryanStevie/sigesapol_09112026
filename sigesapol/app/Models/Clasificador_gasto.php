<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Clasificador_gasto extends Model {

    function getCg() {
        $cad = "Select id,descripcion_cg, estado From clasificador_gastos Where estado=1 Order By id";
        $data = DB::select($cad);
        return $data;
    }

    function getTodoCg() {
        $cad = "Select id,descripcion_cg, estado From clasificador_gastos Order By id";
        $data = DB::select($cad);
        return $data;
    }    

    public function cg_yaregistrada($dato){
        $datos=DB::table('clasificador_gastos') 
                ->where('clasificador_gastos.descripcion_cg','=',$dato)
                ->select('clasificador_gastos.*')
                ->first();
        return $datos;                      
    }

}