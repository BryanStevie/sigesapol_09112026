<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Categoria extends Model {

    //
    public function ShowCategoria() {
        $cad = "select f.* from categorias f where estado = 'N' Order By f.descripcion asc ";
        $data = DB::select($cad);
        return $data;
    }

    public function BuscaCategoria($compara){
    	$cad = "select id  from categorias where descripcion ='".$compara."'";
        $data = DB::select($cad);
        return $data[0];
    }

}
