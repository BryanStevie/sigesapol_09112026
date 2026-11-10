<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Forma_ingreso extends Model {

   	protected $table = 'forma_ingresos';
    protected $guarded = ['id'];
    public $timestamps = false;
    //
    public function ShowForma_ingreso() {
        $cad = "select f.* from forma_ingresos f Order By f.descripcion Asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowAll(){
		$cad = "SELECT id, codigo, descripcion, estado FROM forma_ingresos ORDER BY id;";
        $data = DB::select($cad);
        return $data;
    }
}
