<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Carga_pecosa extends Model
{
    //
    public function GetProductoAgregado($id) {
       $datos=DB::table('carga_pecosas')
               ->join('productos','carga_pecosas.id_producto','=','productos.id')
               ->join('unidades','productos.id_unidad','=','unidades.id')
               ->where('carga_pecosas.id','=',$id)
               ->where('carga_pecosas.estado','=',1)
               ->select('carga_pecosas.*','productos.nombre','productos.codigo','productos.presentacion_comercial as presentacion','unidades.abreviatura as unidad')
               ->first();
       return $datos;
    }


    public function GetCargaPecosa($id_pecosa,$id_farmacia) {
      
      $cad="Select pe.numero nro_pecosa, pe.fecha_entrada fechaentrada_pecosa, dpe.id_producto, p.codigo codigo_producto, p.nombre nombre_producto, Sum(dpe.cantidad) cantidadtot_producto, f.nombre nombre_farmacia
    From guia_entradas pe Inner Join carga_pecosas dpe On pe.id=dpe.id_guia_entrada 
    Inner Join productos p On dpe.id_producto=p.id 
    Inner Join farmacias f On pe.id_farmacia=f.id 
    Where pe.id_farmacia=".$id_farmacia." And pe.id=".$id_pecosa." And dpe.estado=1 
    Group by pe.numero, pe.fecha_entrada, dpe.id_producto, p.codigo, p.nombre, f.nombre ";        
      $data = DB::select($cad);
        return $data;
    }

    public function GetCargaPecosaProducto($id_pecosa,$id_farmacia,$id_producto) {
      
      $cad="Select pe.numero nro_pecosa, pe.fecha_entrada fechaentrada_pecosa, dpe.id_producto, p.codigo codigo_producto, p.nombre nombre_producto, Sum(dpe.cantidad) cantidadtot_producto, f.nombre nombre_farmacia
    From guia_entradas pe Inner Join carga_pecosas dpe On pe.id=dpe.id_guia_entrada 
    Inner Join productos p On dpe.id_producto=p.id 
    Inner Join farmacias f On pe.id_farmacia=f.id 
    Where pe.id_farmacia=".$id_farmacia." And pe.id=".$id_pecosa." And dpe.id_producto=".$id_producto." And dpe.estado=1 
    Group by pe.numero, pe.fecha_entrada, dpe.id_producto, p.codigo, p.nombre, f.nombre ";        
      $data = DB::select($cad);
        return $data;
    }

    public function ContarProducto($id_guia_entrada,$id_producto)
    {
      $cad="select count(*)contador,SUM(cantidad)cantidad, id_producto from carga_pecosas where estado=1 and id_producto=".$id_producto." and id_guia_entrada=".$id_guia_entrada." group by id_producto";
      $data = DB::select($cad);
      return $data;
    }

    


}
