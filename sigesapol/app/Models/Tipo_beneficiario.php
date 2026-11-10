<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tipo_beneficiario extends Model {

    //
    public function ShowTipo_beneficiario() {
        $cad = "select f.* from tipo_beneficiarios f where estado = 'N' Order By f.descripcion asc ";
        $data = DB::select($cad);
        return $data;
    }


    public function getTipoBeneficiarioByID($id)
    {
      $cad = "select f.* from tipo_beneficiarios f where estado = 'N' and id = {$id} Order By f.descripcion asc ";
      $data = DB::select($cad);
      return $data[0];
    } 

    public function getTipoBeneficiarioDescripcion($descripcion)
    {
      $cad = "select id, descripcion from tipo_beneficiarios f where estado = 'N' and descripcion = '{$descripcion}' Order By f.descripcion asc ";
      $data = DB::select($cad);
      return $data[0];
    }

}
