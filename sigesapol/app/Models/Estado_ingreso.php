<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Estado_ingreso extends Model {

    protected $table = 'estado_ingresos';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function ShowEstado_ingreso() {
        $cad = "select f.* from estado_ingresos f Order By f.descripcion Asc";
        $data = DB::select($cad);
        return $data;
    }

}
