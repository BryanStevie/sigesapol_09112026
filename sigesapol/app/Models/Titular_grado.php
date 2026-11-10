<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Titular_grado extends Model
{
    public function ShowTipoGrado(){
    	$cad = "select id, descripcion,descripcion_corta from titular_grados where estado='1' Order By descripcion asc";
        $data = DB::select($cad);
        return $data;
    }

    public function BuscaGrado($compara){
    	$cad = "select id  from titular_grados where descripcion ='".$compara."'";
        $data = DB::select($cad);
        return $data[0];
    }

    public function BuscaGradoD($compara){
        $cad = "select descripcion from titular_grados where  id ='".$compara."'";
        $data = DB::select($cad);
        return $data[0];
    }

    public function ShowTipoGrado_v2 (){
    	$cad = "select id, descripcion from titular_grados where estado = 1 order by descripcion asc";
      $data = DB::select($cad);
      return $data;
    }

}