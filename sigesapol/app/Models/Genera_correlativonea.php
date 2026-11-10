<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Genera_correlativonea extends Model {

    public function obtenerUltiNroNeaByFarmaAndAnio($id_farmacia) {

        $cad = "Select COALESCE(max(nro_nea),0) + 1 nro_nea From genera_correlativoneas Where anio_nea=".date("Y")." And id_farmacia=".$id_farmacia.";";
        $data = DB::select($cad);
        return $data;
    }
    
}
