<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Unidadpago extends Model {

    public function ShowUnidadpago(){
    	$cad = "select id, unipago, descripcion_corta, descripcion_larga from unidadpagos Order By descripcion_larga asc";
        $data = DB::select($cad);
        return $data;
    }

    public function BuscaUnidad($compara){
    	$cad = "select id  from unidadpagos where descripcion_larga ='".$compara."'";
        $data = DB::select($cad);
        return $data[0];
    }

    public function BuscaUnidadD($compara){
        $cad = "select descripcion_larga from unidadpagos where  id =".$compara."";
        $data = DB::select($cad);
        return $data[0];
    }
}
