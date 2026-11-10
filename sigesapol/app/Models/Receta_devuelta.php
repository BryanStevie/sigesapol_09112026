<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Receta_devuelta extends Model
{
    //
	/*
    public function ProductosToDevolver($id_farmacia, $nro_receta,$id_receta){
        $data = DB::table('kardexes') 
                ->join('receta_vales','kardexes.numero_movimiento','=','receta_vales.nro_receta')
                ->join('farmacias','kardexes.nombre_farmacia','=','farmacias.nombre')
                ->join('stock_farmacias','kardexes.codigo_producto','=','stock_farmacias.codigo_producto')
                ->where('kardexes.tipo_movimiento','RC')                
                ->where('kardexes.numero_movimiento',$nro_receta)
                ->where('stock_farmacias.id_farmacia',$id_farmacia)
                ->where('receta_vales.id','=',$id_receta)
                ->where('receta_vales.estado','=',1)
                ->select('stock_farmacias.id','stock_farmacias.codigo_producto','stock_farmacias.nombre_producto',
                        'kardexes.cantidad','receta_vales.estado','kardexes.id as idkardex')
                ->groupBy('stock_farmacias.id','stock_farmacias.codigo_producto','stock_farmacias.nombre_producto',
                        'kardexes.cantidad','receta_vales.estado','kardexes.id')
                 ->get();
         //var_dump($data); exit;
         return $data;
    }
    */
	
	public function ProductosToDevolver($id_farmacia, $nro_receta,$id_receta){
	
		$cad = "select stock_farmacias.id,stock_farmacias.codigo_producto,stock_farmacias.nombre_producto,
				kardexes.cantidad,receta_vales.estado,kardexes.id as idkardex 
				from kardexes
				inner join farmacias on kardexes.nombre_farmacia=farmacias.nombre
				inner join receta_vales on kardexes.numero_movimiento=receta_vales.nro_receta and receta_vales.id_farmacia =farmacias.id
				inner join stock_farmacias on kardexes.codigo_producto=stock_farmacias.codigo_producto
				where kardexes.tipo_movimiento = 'RC'
				and kardexes.numero_movimiento = '".$nro_receta."'
				and stock_farmacias.id_farmacia = ".$id_farmacia."
				and receta_vales.id = ".$id_receta."
				and receta_vales.estado = 1
				group By stock_farmacias.id,stock_farmacias.codigo_producto,stock_farmacias.nombre_producto,
				kardexes.cantidad,receta_vales.estado,kardexes.id";
        
        $data = DB::select($cad);
        return $data;
		
		
	}
	
	
	public function ProductosADevolverKardexAndRecetaVale($id_farmacia,$id_receta){
	
		$cad = "select 
productos.id id_producto,kardexes.codigo_producto,kardexes.nombre_producto,
kardexes.cantidad,receta_vales.estado,kardexes.id as idkardex 
from kardexes
inner join farmacias on kardexes.nombre_farmacia=farmacias.nombre
inner join receta_vales On kardexes.id_nro_movimiento=receta_vales.id And receta_vales.id_farmacia =farmacias.id
inner join productos on kardexes.id_producto=productos.id 
where kardexes.tipo_movimiento = 'RC'
and farmacias.id = '".$id_farmacia."'
and receta_vales.id = '".$id_receta."'
and receta_vales.estado = 1 
order By kardexes.id asc ";
        
        $data = DB::select($cad);
        return $data;
		
		
	}
	
	
    public function ProductosToDevolverVersionMejorada($id_farmacia, $nro_receta,$id_receta){
        $data = DB::table('kardexes') 
                ->join('receta_vales','kardexes.numero_movimiento','=','receta_vales.nro_receta')
                ->join('farmacias','kardexes.nombre_farmacia','=','farmacias.nombre')
                ->join('stock_farmacias','kardexes.codigo_producto','=','stock_farmacias.codigo_producto')
                //->where('farmacias.id',$id_farmacia)
                ->where('kardexes.tipo_movimiento','RC')                
                ->where('kardexes.numero_movimiento',$nro_receta)
                ->where('stock_farmacias.id_farmacia',$id_farmacia)
                ->where('receta_vales.id','=',$id_receta)
                ->where('receta_vales.estado','=',1)
                ->select('stock_farmacias.id','stock_farmacias.codigo_producto','stock_farmacias.nombre_producto',
                        'kardexes.cantidad','receta_vales.estado')
                ->groupBy('stock_farmacias.id','stock_farmacias.codigo_producto','stock_farmacias.nombre_producto',
                        'kardexes.cantidad','receta_vales.estado')
                 ->get();         
         return $data;
    }
	
	/*
    public function LotesToDevolver($id_farmacia, $nro_receta, $id_receta){
        $data = DB::table('kardexes') 
                ->join('receta_vales','kardexes.numero_movimiento','=','receta_vales.nro_receta')
                ->join('farmacias','kardexes.nombre_farmacia','=','farmacias.nombre')
                ->join('kardex_lotes','kardexes.id','=','kardex_lotes.id_kardex')
                ->join('stock_lotes','kardex_lotes.id_stock_lote','=','stock_lotes.id')
                ->join('stock_farmacias','stock_lotes.id_stock_farmacia','=','stock_farmacias.id')
                ->where('kardexes.tipo_movimiento','RC')
                ->where('farmacias.id',$id_farmacia)
                ->where('receta_vales.id','=',$id_receta)
                ->where('kardexes.numero_movimiento',$nro_receta)
                ->where('receta_vales.estado','=',1)
                ->select('stock_lotes.id','stock_farmacias.id as id_stock_farmacia','stock_lotes.lote','stock_farmacias.codigo_producto',
                        'stock_farmacias.nombre_producto','kardex_lotes.cantidad') 
                ->orderBy('stock_lotes.id','asc')
                 ->get();
        //var_dump($data); exit;
         return $data;
    }
	*/
	
	public function LotesToDevolver($id_farmacia, $nro_receta, $id_receta){
        
		$cad = "select 
				stock_lotes.id,stock_farmacias.id as id_stock_farmacia,stock_lotes.lote,stock_farmacias.codigo_producto,
				stock_farmacias.nombre_producto,kardex_lotes.cantidad
				from kardexes
				inner join farmacias on kardexes.nombre_farmacia=farmacias.nombre
				inner join receta_vales on kardexes.numero_movimiento=receta_vales.nro_receta and receta_vales.id_farmacia =farmacias.id
				inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
				inner join stock_lotes on kardex_lotes.id_stock_lote=stock_lotes.id
				inner join stock_farmacias on stock_lotes.id_stock_farmacia=stock_farmacias.id
				where kardexes.tipo_movimiento = 'RC' 
				and farmacias.id=".$id_farmacia." 
				and receta_vales.id=".$id_receta." 
				and kardexes.numero_movimiento='".$nro_receta."' 
				and receta_vales.estado=1 
				order By stock_lotes.id asc";
        
        $data = DB::select($cad);
        return $data;
		
    }
	
	public function LotesADevolverKardexAndRecetaVale($id_farmacia,$id_receta,$id_producto){
        
		$cad = "select 
kardex_lotes.id,kardex_lotes.lote,kardexes.codigo_producto,kardexes.nombre_producto
,kardex_lotes.lote,kardex_lotes.fecha_vencimiento,kardex_lotes.registro_sanitario,kardex_lotes.cantidad,kardex_lotes.fecha_registro, kardex_lotes.precio_unitario, kardex_lotes.presentacion_comercial 
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join farmacias on kardexes.nombre_farmacia=farmacias.nombre
inner join receta_vales On kardexes.id_nro_movimiento=receta_vales.id And receta_vales.id_farmacia =farmacias.id
inner join productos on kardexes.id_producto=productos.id 
where kardexes.tipo_movimiento = 'RC'
and farmacias.id = '".$id_farmacia."' 
and receta_vales.id = '".$id_receta."' 
and productos.id = '".$id_producto."' 
and receta_vales.estado = 1 
order By kardexes.id asc";
		
        $data = DB::select($cad);
        return $data;
		
    }
	
	
	
    
   public function GetNumMovimiento($id_farmacia){
        $id = DB::table('receta_devueltas')
                ->where('receta_devueltas.id_farmacia','=',$id_farmacia)
                ->select(DB::raw('max(receta_devueltas.id) as ultimo'))
                ->first();
       return $id;
   }
   public function GetNroRecetaByNroRecera($id_receta, $id_farmacia){
        $data = DB::table('receta_vales')
                ->where('receta_vales.id','=',$id_receta)
                ->where('receta_vales.id_farmacia','=',$id_farmacia)
                ->where('receta_vales.estado','=',1)
                ->first();
        //var_dump($data); exit;
       return $data;
   }
}

