<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tipo_documento_identidad extends Model
{
    protected $table = 'tipo_documento_identidad';
    public function ShowTipoDocumentoIdentidad(){
    	$cad = "select id, descripcion from tipo_documento_identidad where estado='1' Order By id asc";
        $data = DB::select($cad);
        return $data;
    }

    public function BuscaTipoDocumentoIdentidad($tipo_documento){
        $cad = "select id, descripcion from tipo_documento_identidad where estado='1' And id=" . $tipo_documento . " Order By id asc";
        $data = DB::select($cad);
        return $data[0];
    }
}