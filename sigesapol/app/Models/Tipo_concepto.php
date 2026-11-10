<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tipo_concepto extends Model {

    function getTipoConcepto() {
        $cad = "Select id,descripcion_tipo_concepto From tipo_conceptos Where estado=1 Order By id";
        $data = DB::select($cad);
        return $data;
    }

}