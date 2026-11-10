<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Devolucion_producto extends Model
{
    //
    public function ObtenerProductoPorIdDevolucion($id_devolucion) {
       $datos=DB::table('devolucion_productos')
               ->join('devoluciones','devolucion_productos.id_devolucion','=','devoluciones.id')
               ->join('productos','devolucion_productos.id_producto','=','productos.id')
               ->join('unidades','productos.id_unidad','=','unidades.id')
               ->join('stock_lotes','devolucion_productos.id_stock_lote','=','stock_lotes.id')
               ->where('devolucion_productos.id_devolucion','=',$id_devolucion)
               ->where('devoluciones.estado','>',2)
               ->where('devolucion_productos.estado','=',1)
               ->select('productos.id as id_producto','productos.nombre as nombre_producto','productos.codigo as codigo_producto','unidades.abreviatura as unidad',
                       'stock_lotes.id as id_stock_lote','stock_lotes.lote','stock_lotes.registro_sanitario','stock_lotes.fecha_vencimiento',
                       'devolucion_productos.id_devolucion', 'devoluciones.nro_acta','devolucion_productos.cantidad')
               ->get();
       //var_dump($datos); exit;
       return $datos;
    }
	
	public function ObtenerProductoPorIdDevolucionAgrupadoByProducto($id_devolucion){
        
		$cad = "select productos.id as id_producto,productos.nombre as nombre_producto,productos.codigo as codigo_producto,devoluciones.nro_acta
,SUM(devolucion_productos.cantidad)cantidad
from devolucion_productos
inner join devoluciones on devolucion_productos.id_devolucion=devoluciones.id
inner join productos on devolucion_productos.id_producto=productos.id
where devolucion_productos.id_devolucion=".$id_devolucion."
and devoluciones.estado>2
and devolucion_productos.estado=1
group by productos.id,productos.nombre,productos.codigo,devoluciones.nro_acta ";
		
        $data = DB::select($cad);
        return $data;
		
    }
	
	public function ObtenerProductoPorIdDevolucionAgrupadoByProductoInterna($id_devolucion){
        
		$cad = "select productos.id as id_producto,productos.nombre as nombre_producto,productos.codigo as codigo_producto,devoluciones.nro_acta
,SUM(devolucion_productos.cantidad)cantidad
from devolucion_productos
inner join devoluciones on devolucion_productos.id_devolucion=devoluciones.id
inner join productos on devolucion_productos.id_producto=productos.id
where devolucion_productos.id_devolucion=".$id_devolucion."
and devolucion_productos.estado=1
group by productos.id,productos.nombre,productos.codigo,devoluciones.nro_acta ";
		
        $data = DB::select($cad);
        return $data;
		
    }
	
	public function ObtenerProductoPorIdDevolucionAndIdProducto($id_devolucion,$id_producto=0){
        
		$cad = "select 
productos.id as id_producto,productos.nombre as nombre_producto,productos.codigo as codigo_producto,unidades.abreviatura as unidad,
devolucion_productos.lote,devolucion_productos.registro_sanitario,devolucion_productos.fecha_vencimiento,
devolucion_productos.id_devolucion,devoluciones.nro_acta,devolucion_productos.cantidad,devolucion_productos.precio_unitario 
from devolucion_productos
inner join devoluciones on devolucion_productos.id_devolucion=devoluciones.id
inner join productos on devolucion_productos.id_producto=productos.id
inner join unidades on productos.id_unidad=unidades.id
where devolucion_productos.id_devolucion=".$id_devolucion."
and devoluciones.estado>2
and devolucion_productos.estado=1";
	if($id_producto > 0)$cad .= " and devolucion_productos.id_producto=".$id_producto;

        $data = DB::select($cad);
        return $data;
		
    }
	
	
	public function ObtenerProductoPorIdDevolucionAndIdProductoInterna($id_devolucion,$id_producto=0){
        
		$cad = "select 
productos.id as id_producto,productos.nombre as nombre_producto,productos.codigo as codigo_producto,unidades.abreviatura as unidad,
devolucion_productos.lote,devolucion_productos.registro_sanitario,devolucion_productos.fecha_vencimiento,
devolucion_productos.id_devolucion,devoluciones.nro_acta,devolucion_productos.cantidad,devolucion_productos.precio_unitario 
from devolucion_productos
inner join devoluciones on devolucion_productos.id_devolucion=devoluciones.id
inner join productos on devolucion_productos.id_producto=productos.id
inner join unidades on productos.id_unidad=unidades.id
where devolucion_productos.id_devolucion=".$id_devolucion."
and devolucion_productos.estado=1";
	if($id_producto > 0)$cad .= " and devolucion_productos.id_producto=".$id_producto;

        $data = DB::select($cad);
        return $data;
		
    }
	
	
}
