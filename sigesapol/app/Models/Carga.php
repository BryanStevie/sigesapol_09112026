<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Carga extends Model
{
    //
    public function  ShowCargasLotesById_farmacia($id_farmacia){
        $datos=DB::table('cargas')                
                ->select('cargas.*')   
                ->where('cargas.id_farmacia','=',$id_farmacia)               
                ->get();
        return $datos;
    }
    
    
    public function ShowCargasById_farmacia($id_farmacia) {
        $datos=DB::table('cargas')                
                ->select('cargas.codigo','cargas.nombre',DB::raw('sum(cargas.cantidad) as cantidad'),'cargas.id_farmacia')   
                ->where('cargas.id_farmacia','=',$id_farmacia)
                ->groupBy('cargas.codigo','cargas.nombre','cargas.id_farmacia')
                ->get();
        return $datos;
    }
    
    public function ShowProductosCargadosByIdCabecera($id_carga_inicial) {
        $datos=DB::table('cargas')                
                ->join('carga_inicial_stocks','cargas.id_carga_inicial','=','carga_inicial_stocks.id')
                ->join('productos','cargas.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->where('cargas.id_carga_inicial','=',$id_carga_inicial)
                ->where('cargas.estado',1)
                ->select('cargas.*','productos.nombre as nombre_producto','productos.codigo as codigo_producto',
                        'unidades.abreviatura as unidad')
                ->get();
        return $datos;
    }
    
    public function ValidaLoteDuplicado($lote, $id_producto, $id_carga_inicial) {
        $datos=DB::table('cargas')
                ->where('lote','=',$lote)
                ->where('id_producto','=',$id_producto)
                ->where('id_carga_inicial','=',$id_carga_inicial)
                ->count();
        return $datos;
    }
    
    public function GetProductoById_carga($id) {
        $datos=DB::table('cargas')
                ->join('productos','cargas.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->where('cargas.id',$id)
                ->select('cargas.*','productos.nombre as nombre_producto','productos.codigo as codigo_producto',
                        'unidades.abreviatura as unidad')
                ->first();
        return $datos;
    }
    
	public function ObtenerProductoPorIdCargaInicialAgrupadoByProducto($id_carga_inicial){
        
		$cad = "select 
cargas.fecha_ingreso,farmacias.nombre nombre_farmacia,
productos.id as id_producto,productos.nombre as nombre_producto,productos.codigo as codigo_producto,SUM(cargas.cantidad)cantidad
-- ,precio_promedio
-- ,(select precio_promedio from cargas c where c.id_carga_inicial=cargas.id_carga_inicial and id_producto=productos.id limit 1)precio_promedio
from cargas
inner join carga_inicial_stocks on cargas.id_carga_inicial=carga_inicial_stocks.id
inner join productos on cargas.id_producto=productos.id
inner join unidades on productos.id_unidad=unidades.id
inner join farmacias on cargas.id_farmacia=farmacias.id
where cargas.id_carga_inicial=".$id_carga_inicial."
and cargas.estado=1
group by cargas.id_carga_inicial,cargas.fecha_ingreso,farmacias.nombre,productos.id,productos.nombre,productos.codigo";
		
        $data = DB::select($cad);
        return $data;
		
    }
	
	public function ObtenerProductoPorIdCargaInicialAndIdProducto($id_carga_inicial,$id_producto){
        
		$cad = "select cargas.lote,cargas.fecha_vencimiento,cargas.registro_sanitario,cargas.cantidad,cargas.fecha_ingreso,precio_promedio, cargas.id
from cargas
inner join carga_inicial_stocks on cargas.id_carga_inicial=carga_inicial_stocks.id
inner join productos on cargas.id_producto=productos.id
inner join unidades on productos.id_unidad=unidades.id
where cargas.id_carga_inicial=".$id_carga_inicial."
and cargas.id_producto = ".$id_producto."
and cargas.estado=1";
		
        $data = DB::select($cad);
        return $data;
		
    }
	
	
    protected $fillable = ['codigo','nombre','registro_sanitario','lote','cantidad','fecha_vencimiento','id_farmacia','fecha_ingreso'];
	
	
}
