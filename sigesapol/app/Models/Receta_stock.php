<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Receta_stock extends Model
{
    //
    public function ShowMedicamentosAtendidos($id_receta){
        $datos=DB::table('receta_stocks') 
                ->join('receta_atendidas','receta_stocks.id_receta','=','receta_atendidas.id')
                ->join('stocks','receta_stocks.id_stock','=','stocks.id') 
                ->join('productos','stocks.id_producto','=','productos.id') 
                ->select('productos.*','receta_stocks.cantidad_solicitada as solicitados','receta_stocks.cantidad_atendida as atendidos',
                        'receta_stocks.id as id_receta_stock','receta_stocks.created_at')
                ->where('receta_stocks.id_receta','=',$id_receta)  
                ->orderby('productos.id','asc')
                ->get();
        return $datos;
    }
    
    public function GetMedicamentoAtendido($id){
        $datos=DB::table('receta_stocks') 
                ->join('receta_atendidas','receta_stocks.id_receta','=','receta_atendidas.id')
                ->join('stocks','receta_stocks.id_stock','=','stocks.id') 
                ->join('productos','stocks.id_producto','=','productos.id') 
                ->select('productos.*','receta_stocks.cantidad_solicitada as solicitados','receta_stocks.cantidad_atendida as atendidos',
                        'receta_stocks.id as id_receta_stock','receta_atendidas.*','receta_stocks.created_at','stocks.monto_actual')
                ->where('receta_stocks.id','=',$id)                
                ->first();
        return $datos;
    }


    public function ExisteMedicamento($receta, $medicamento) {
        $data = Receta_stock::where('id_receta', '=', $receta)
                ->where('id_stock','=',$medicamento)
                ->first();
        //var_dump($data); exit;
        return isset($data) ? true : false;
    }
}
