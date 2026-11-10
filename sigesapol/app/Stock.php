<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Stock extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stocks';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['monto_inicial', 'monto_actual', 'monto_consumido', 'fecha_registro', 'estado','id_user'.'id_farmacia','id_producto'];
    
    public function ExisteStock($idprod, $idfarma) {
        $data = Stock::where('id_producto', '=', $idprod)
                ->where('id_farmacia', '=', $idfarma)
                ->where('estado','=',1)
                ->first();
        return isset($data) ? true : false;
    }
    public function ShowMedicamentoByFarma($id_farma){
        $datos=DB::table('stocks') 
                ->join('productos','stocks.id_producto','=','productos.id')                
                ->select('productos.*','stocks.monto_actual','stocks.id as id_stock_farmacia')
                ->where('stocks.id_farmacia','=',$id_farma)
                ->orderby('productos.id','asc')
                ->get();       
        return $datos;          
    }
    
    public function ObtenerStockByMedicamento($medicamento){
        $datos=DB::table('stocks')                
                ->select('stocks.*')
                ->where('stocks.id_producto','=',$medicamento)
                ->first();       
        return $datos; 
    }
    
    public function GetProductoByIdproductoAndIdfarma($idprod,$idfarma){
        $datos=DB::table('stocks')                
                ->select('stocks.*')
                ->where('stocks.id_producto','=',$idprod)
                ->where('stocks.id_farmacia','=',$idfarma)
                ->where('stocks.estado','=',1)
                ->first();       
        return $datos; 
    }
}
