<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tipo_proceso extends Model {

    function getTipoProceso() {
        $cad = "Select id,descripcion_tipo_proceso, abreviatura_tipo_proceso,estado From tipo_procesos Where estado=1 Order By id";
        $data = DB::select($cad);
        return $data;
    }

    function getTodoTipoProceso() {
        $cad = "Select id,descripcion_tipo_proceso, abreviatura_tipo_proceso,estado From tipo_procesos Order By id";
        $data = DB::select($cad);
        return $data;
    }

    

    public function tipoproceso_yaregistrada($abreviatura){
        $datos=DB::table('tipo_procesos') 
                ->where('tipo_procesos.abreviatura_tipo_proceso','=',$abreviatura)
                ->select('tipo_procesos.*')
                ->first();
        return $datos;                      
    }

}