<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Fema_examene extends Model {
    public $timestamps = false;
    public function ShowPrioridad(){
    	$cad = "select id, codigo, descripcion as prioridad  from prioridades f where estado = 'N' Order By id asc";
        $data = DB::select($cad);
        return $data;
    }
}
