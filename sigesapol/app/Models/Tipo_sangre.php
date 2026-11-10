<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tipo_sangre extends Model
{
    public function ShowAll(){
    	$cad = "select id, descripcion_corta, descripcion,grupo_sanguineo,factor_rh from tipo_sangres where estado='1' Order By descripcion asc";
        $data = DB::select($cad);
        return $data;
    }
}