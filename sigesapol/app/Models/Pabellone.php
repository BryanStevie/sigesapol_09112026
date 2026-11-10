<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Pabellone extends Model {

    public function ShowallById_Ipress($id_ipress, $nombre_atencion) {
        $cad = "select id, nombre, nombre_atencion
                from pabellones
                where pabellones.nombre_atencion='".$nombre_atencion."' and pabellones.id_establecimiento=" . $id_ipress;
        $cad .= " And COALESCE(estado,1)!=3  ";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowallByIdIpress($id_ipress) {
        $cad = "select id, nombre, nombre_atencion
                from pabellones
                where  pabellones.id_establecimiento=" . $id_ipress;
        $cad .= " And COALESCE(estado,1)!=3  ";
        $data = DB::select($cad);
        return $data;
    }

    public function ExistePabellon($nombre, $id_establecimiento, $id = 0) {
        $data = Pabellone::where('nombre', '=', trim($nombre))->where('id_establecimiento', '=', $id_establecimiento)->WhereNull('estado')->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }

}
