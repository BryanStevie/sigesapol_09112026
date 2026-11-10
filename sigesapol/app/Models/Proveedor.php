<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Proveedor extends Model {

    function getProveedor() {
        $cad = "Select id,ruc_proveedor, nombre_proveedor From proveedores Where estado=1 Order By id";
        $data = DB::select($cad);
        return $data;
    }

}