<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Titular_categoria extends Model
{
    public function ShowTipoCategoria(){
    	$cad = "select id, descripcion from titular_categorias where estado='1' Order By descripcion asc";
        $data = DB::select($cad);
        return $data;
    }

    public function BuscaTipoCategoria($compara){
    	$cad = "select id  from titular_categorias where descripcion ='".$compara."'";
        $data = DB::select($cad);
        return $data[0];
    }

    public function BuscaTipoCategoriaD($compara){
    	$cad = "select descripcion from titular_categorias where  id ='".$compara."'";
        $data = DB::select($cad);
        return $data[0];
    }
}