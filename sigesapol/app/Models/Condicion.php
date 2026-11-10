<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Condicion extends Model {

    public function ShowCondicion(){
    	$cad = "select id, codigo, descripcion, estado from condiciones where estado = 'N' Order by descripcion asc";
        $data = DB::select($cad);
        return $data;
    }

}
