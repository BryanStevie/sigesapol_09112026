<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tipo_documento extends Model {

    function getTipoDocumento($tipo) {
        $cad = "Select id,descripcion_tipo_documento From tipo_documentos Where id_concepto=".$tipo." and estado=1 Order By id";
        $data = DB::select($cad);
        return $data;
    }

}