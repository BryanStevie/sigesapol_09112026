<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Transferencia_producto extends Model
{
    //
    public function ObtenerProductoPorIdTransferencia($id_transferencia) {
       $datos=DB::table('transferencia_productos')
               ->join('transferencias','transferencia_productos.id_transferencia','=','transferencias.id')
               ->join('productos','transferencia_productos.id_producto','=','productos.id')
               ->join('unidades','productos.id_unidad','=','unidades.id')
               ->join('stock_lotes','transferencia_productos.id_stock_lote','=','stock_lotes.id')
               ->where('transferencia_productos.id_transferencia','=',$id_transferencia)
               ->where('transferencias.estado','>',2)
               ->where('transferencia_productos.estado','=',1)
               ->select('productos.id as id_producto','productos.nombre as nombre_producto','productos.codigo as codigo_producto','unidades.abreviatura as unidad',
                       'stock_lotes.id as id_stock_lote','stock_lotes.lote','stock_lotes.registro_sanitario','stock_lotes.fecha_vencimiento',
                       'transferencia_productos.id_transferencia', 'transferencias.nro_acta','transferencia_productos.cantidad')
               ->get();
       //var_dump($datos); exit;
       return $datos;
    }
	
	public function ObtenerProductoPorIdTransferenciaAgrupadoByProducto($id_transferencia){
        
		$cad = "select productos.id as id_producto,productos.nombre as nombre_producto,productos.codigo as codigo_producto,transferencias.nro_acta
,SUM(transferencia_productos.cantidad)cantidad
from transferencia_productos
inner join transferencias on transferencia_productos.id_transferencia=transferencias.id
inner join productos on transferencia_productos.id_producto=productos.id
where transferencia_productos.id_transferencia=".$id_transferencia."
and transferencias.estado>2
and transferencia_productos.estado=1
group by productos.id,productos.nombre,productos.codigo,transferencias.nro_acta ";
		
        $data = DB::select($cad);
        return $data;
		
    }
	
	public function ObtenerProductoPorIdTransferenciaAgrupadoByProductoInterna($id_transferencia){
        
		$cad = "select productos.id as id_producto,productos.nombre as nombre_producto,productos.codigo as codigo_producto,transferencias.nro_acta
,SUM(transferencia_productos.cantidad)cantidad
from transferencia_productos
inner join transferencias on transferencia_productos.id_transferencia=transferencias.id
inner join productos on transferencia_productos.id_producto=productos.id
where transferencia_productos.id_transferencia=".$id_transferencia."
and transferencia_productos.estado=1
group by productos.id,productos.nombre,productos.codigo,transferencias.nro_acta ";
		
        $data = DB::select($cad);
        return $data;
		
    }
	
	public function ObtenerProductoPorIdTransferenciaAndIdProducto($id_transferencia,$id_producto=0){
        
		$cad = "select 
productos.id as id_producto,productos.nombre as nombre_producto,productos.codigo as codigo_producto,unidades.abreviatura as unidad,
transferencia_productos.lote,transferencia_productos.registro_sanitario,to_char(transferencia_productos.fecha_vencimiento,'dd-mm-yyyy')fecha_vencimiento,
transferencia_productos.id_transferencia,transferencias.nro_acta,transferencia_productos.cantidad,transferencia_productos.precio_unitario, transferencia_productos.id , transferencia_productos.presentacion_comercial
from transferencia_productos
inner join transferencias on transferencia_productos.id_transferencia=transferencias.id
inner join productos on transferencia_productos.id_producto=productos.id
inner join unidades on productos.id_unidad=unidades.id
where transferencia_productos.id_transferencia=".$id_transferencia."
and transferencias.estado>2
and transferencia_productos.estado=1";
	if($id_producto > 0)$cad .= " and transferencia_productos.id_producto=".$id_producto;
        $data = DB::select($cad);
        return $data;
		
    }
	
	
	public function ObtenerProductoPorIdTransferenciaAndIdProductoInterna($id_transferencia,$id_producto=0){
        
		$cad = "select 
productos.id as id_producto,productos.nombre as nombre_producto,productos.codigo as codigo_producto,unidades.abreviatura as unidad,
transferencia_productos.lote,transferencia_productos.registro_sanitario,transferencia_productos.fecha_vencimiento,
transferencia_productos.id_transferencia,transferencias.nro_acta,transferencia_productos.cantidad,transferencia_productos.precio_unitario, transferencia_productos.id , transferencia_productos.presentacion_comercial
from transferencia_productos
inner join transferencias on transferencia_productos.id_transferencia=transferencias.id
inner join productos on transferencia_productos.id_producto=productos.id
inner join unidades on productos.id_unidad=unidades.id
where transferencia_productos.id_transferencia=".$id_transferencia."
and transferencia_productos.estado=1";
	if($id_producto > 0)$cad .= " and transferencia_productos.id_producto=".$id_producto;

        $data = DB::select($cad);
        return $data;
		
    }

    public function Contar($id_transferencia,$id_producto)
    {
      $cad="select count(*)contador,SUM(cantidad)cantidad, id_producto from transferencia_productos where estado=1 and id_producto=".$id_producto." and id_transferencia=".$id_transferencia." group by id_producto";
      $data = DB::select($cad);
      return $data;
    }

    

}
