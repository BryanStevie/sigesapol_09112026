<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Req_producto extends Model {

    //
    public function ProductosByRequerimiento($id_requerimiento) {
        $productos = DB::table('req_productos')
                ->join('productos', function ($join) {
                    $join->on('req_productos.codigo_producto', '=', 'productos.codigo')->orOn('req_productos.codigo_producto', '=', 'productos.old_cod');
                })
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->select('req_productos.id','req_productos.id_requerimiento','productos.codigo as codigo_producto','req_productos.cantidad','req_productos.created_at','req_productos.updated_at', 'req_productos.estado',  'unidades.abreviatura', 'productos.nombre')
                ->where('req_productos.id_requerimiento', $id_requerimiento)
                ->where('req_productos.estado', 1)
                ->where('productos.estado', 1)
                ->orderBy('productos.nombre','asc')
                ->get();
        return $productos;
    }

    public function YaSeAgregoEsteProdAEsteRequerimiento($codigo_producto, $id_requerimiento) {
        $productos = DB::table('req_productos')
                ->select('req_productos.*')
                ->where('req_productos.id_requerimiento', $id_requerimiento)
                ->where('req_productos.codigo_producto', $codigo_producto)
                ->get();
        return $productos;
    }

}
