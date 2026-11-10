<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Stock_lote extends Model
{
    //
    public function ShowLotesById_farmacia($id_farmacia){
        $data = DB::table('stock_lotes')
                ->join('stock_farmacias', 'stock_lotes.id_stock_farmacia', '=', 'stock_farmacias.id')  
                ->join('productos','stock_farmacias.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->where('stock_lotes.cantidad','>',0)
                ->select('stock_lotes.*','productos.*','unidades.nombre as unidad')
                ->groupBy('stock_lotes.id','stock_lotes.lote','productos.id','unidades.nombre')
                ->orderBy('productos.nombre','asc')
                ->get();
        return $data;                
    }
    public function ShowLotesTransferirById_farmacia($id_farmacia){
        $data = DB::table('stock_lotes')
                ->join('stock_farmacias', 'stock_lotes.id_stock_farmacia', '=', 'stock_farmacias.id')  
                //->join('productos','stock_farmacias.id_producto','=','productos.id')
                ->join('productos','stock_farmacias.codigo_producto','=','productos.codigo')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->where('stock_farmacias.cantidad','>',0)
                ->select('stock_lotes.*','productos.nombre','productos.codigo','unidades.nombre as unidad')
                ->orderBy('stock_lotes.id','asc')
                ->get();
        //var_dump($data); exit();
        return $data;                
    }
    
    public function getLoteByidlote($id_lote,$id_farmacia){
        $data = DB::table('stock_lotes')
                ->join('stock_farmacias', 'stock_lotes.id_stock_farmacia', '=', 'stock_farmacias.id')  
                //->join('productos','stock_farmacias.id_producto','=','productos.id')
                ->join('productos','stock_farmacias.codigo_producto','=','productos.codigo')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->where('stock_lotes.id','=',$id_lote)
                ->where('stock_farmacias.cantidad','>',0)
                ->select('stock_lotes.*','productos.nombre','productos.codigo','productos.id as id_producto','unidades.nombre as unidad')
                ->orderBy('stock_lotes.id','asc')
                ->get();
        //var_dump($data); exit();
        return $data;                
    }
    

}
