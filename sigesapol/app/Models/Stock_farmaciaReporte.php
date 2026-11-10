<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class Stock_farmaciaReporte extends Model {

    protected $connection = 'pgsql_reportes';
	protected $table = 'stock_farmacias';
    
    public function CountProductosProxVencer($dias,$id_farmacia){
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');      
        $nuevafecha = strtotime('+'.$dias.' day', strtotime($fecha));
        $nuevafecha = date('Y-m-j', $nuevafecha);
        //=====================================
        $contador = DB::table('stock_lotes')
                ->join('stock_farmacias','stock_lotes.id_stock_farmacia','=','stock_farmacias.id')
                ->join('productos','stock_farmacias.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->where('stock_lotes.fecha_vencimiento','>=',$fecha)
                ->where('stock_lotes.fecha_vencimiento','<=',$nuevafecha)
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->where('stock_lotes.cantidad','>',0)                
                ->select('productos.*','stock_lotes.*','unidades.nombre as unidad')
                ->count();
        return $contador;
    }    
    
    public function GetStockPorLotes($id_farmacia) {
        $productos=DB::table('stock_lotes')
                ->join('stock_farmacias','stock_lotes.id_stock_farmacia','=','stock_farmacias.id')
                ->join('productos','stock_farmacias.codigo_producto','=','productos.codigo')
                ->join('unidades','productos.id_unidad','=','unidades.id')                
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->where('stock_lotes.cantidad','>',0)
                ->select('productos.*','stock_lotes.*','unidades.nombre as unidad')
                ->orderBy('productos.nombre', 'asc')
                ->get();
        //var_dump($productos); exit;
        return $productos;
    }
	
	public function GetStockPorLotesByFechaVencimiento($id_farmacia, $fecha_inicio, $fecha_fin) {
        $productos=DB::table('stock_lotes')
                ->join('stock_farmacias','stock_lotes.id_stock_farmacia','=','stock_farmacias.id')
                ->join('productos','stock_farmacias.codigo_producto','=','productos.codigo')
                ->join('unidades','productos.id_unidad','=','unidades.id')                
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->where('stock_lotes.cantidad','>',0)
                ->where('stock_lotes.fecha_vencimiento', '>=', $fecha_inicio)
                ->where('stock_lotes.fecha_vencimiento', '<=', $fecha_fin)
                ->select('productos.*','stock_lotes.*','unidades.nombre as unidad')
                ->orderBy('productos.nombre', 'asc')
                ->get();
        
        return $productos;
    }
    
    public static function GetStockPorLotesByFechaVencimientoAndProducto($id_farmacia, $fecha_inicio, $fecha_fin,$codigo_producto=0) {
        $productos=DB::table('stock_lotes')
                ->join('stock_farmacias','stock_lotes.id_stock_farmacia','=','stock_farmacias.id')
                ->join('productos','stock_farmacias.codigo_producto','=','productos.codigo')
                ->join('unidades','productos.id_unidad','=','unidades.id')                
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->where('stock_lotes.cantidad','>',0)
                ->where('stock_lotes.fecha_vencimiento', '>=', $fecha_inicio)
                ->where('stock_lotes.fecha_vencimiento', '<=', $fecha_fin)
                ->where('stock_farmacias.codigo_producto','=', $codigo_producto)
                ->select('productos.*','stock_lotes.*','unidades.nombre as unidad')
                ->orderBy('productos.nombre', 'asc')
                ->get();
        
        return $productos;
    }
	
    
    public function CountProductos_vencidos($fecha,$id_farmacia){
        $contador = DB::table('stock_lotes')
                ->join('stock_farmacias','stock_lotes.id_stock_farmacia','=','stock_farmacias.id')
                ->join('productos','stock_farmacias.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->where('stock_lotes.fecha_vencimiento','<',$fecha)
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->where('stock_lotes.cantidad','>',0)
                ->select('productos.*','stock_lotes.*','unidades.nombre as unidad')
                ->count();
        return $contador;
    }
    
    public function ProductosProximosVencer($dias,$id_farmacia){
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');      
        $nuevafecha = strtotime('+'.$dias.' day', strtotime($fecha));
        $nuevafecha = date('Y-m-j', $nuevafecha);
        //=====================================
        $productos = DB::table('stock_lotes')
                ->join('stock_farmacias','stock_lotes.id_stock_farmacia','=','stock_farmacias.id')
                ->join('productos','stock_farmacias.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->where('stock_lotes.fecha_vencimiento','>=',$fecha)
                ->where('stock_lotes.fecha_vencimiento','<=',$nuevafecha)
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->where('stock_lotes.cantidad','>',0)
                ->select('productos.*','stock_lotes.*','unidades.nombre as unidad')  
                ->orderBy('stock_lotes.fecha_vencimiento','asc')
                ->get();
        return $productos;
    }

    public function ProductosVencidos($fecha,$id_farmacia){
        $productos = DB::table('stock_lotes')
                ->join('stock_farmacias','stock_lotes.id_stock_farmacia','=','stock_farmacias.id')
                ->join('productos','stock_farmacias.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->where('stock_lotes.fecha_vencimiento','<',$fecha)
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->where('stock_lotes.cantidad','>',0)
                ->select('productos.*','stock_lotes.*','unidades.nombre as unidad')   
                ->orderBy('productos.nombre','asc')
                ->get();
        return $productos;
    }

    public function ProductosDifferentMedicamentos(){
        $productos = DB::table('stock_farmacias')
                ->join('productos','stock_farmacias.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->select('productos.codigo','productos.nombre',DB::raw('sum(stock_farmacias.cantidad) as cantidad'),'unidades.nombre as unidad')
                ->where('productos.id_tipo','!=',1)
                ->groupBy('productos.codigo','productos.nombre','unidades.nombre')
                ->orderBy('productos.nombre','asc')
                ->get();
        return $productos;
    }
    
    public function ProductosByTipoProducto($tipo){
        $productos = DB::table('stock_farmacias')
                ->join('productos','stock_farmacias.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->select('productos.codigo','productos.nombre',DB::raw('sum(stock_farmacias.cantidad) as cantidad'),'unidades.nombre as unidad')
                ->where('productos.id_tipo','=',$tipo)
                ->groupBy('productos.codigo','productos.nombre','unidades.nombre')
                ->orderBy('productos.nombre','asc')
                ->get();
        return $productos;
    }
    
    public function ProductosByFarmacia(){
        $productos = DB::table('stock_farmacias')
                ->join('productos','stock_farmacias.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->select('productos.codigo','productos.nombre',DB::raw('sum(stock_farmacias.cantidad) as cantidad'),'unidades.nombre as unidad')
                ->groupBy('productos.codigo','productos.nombre','unidades.nombre')
                ->orderBy('productos.nombre','asc')
                ->get();
        return $productos;
    }
    

    public function CantidadByFarmacia($id_farmacia, $codigo_producto){
        $cantidad = DB::table('stock_farmacias')
                ->select(DB::raw('coalesce(sum(stock_farmacias.cantidad),0) as cantidad'))
                ->where('stock_farmacias.codigo_producto','=',$codigo_producto)
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)                
                ->first();
        $farmacia=DB::table('farmacias')
                ->where('farmacias.id','=',$id_farmacia)
                ->first();
        $data=[$cantidad,$farmacia];
        return $data;
    }

    public function ShowLotesById_productoAndId_farmacia($id_producto, $id_farmacia, $cantidad_prescrita){
        $producto = DB::table('productos')
                ->where('productos.id','=',$id_producto)
                ->get();
        $codigo_producto=$producto[0]->codigo;
        
        $data = DB::table('stock_farmacias')
                ->join('stock_lotes', 'stock_farmacias.id', '=', 'stock_lotes.id_stock_farmacia')                                
                ->where('stock_farmacias.codigo_producto','=',$codigo_producto)
                ->where('stock_farmacias.id_producto','=',$id_producto)
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->where('stock_lotes.cantidad','>',0)
                ->select('stock_farmacias.nombre_producto','stock_farmacias.codigo_producto','stock_lotes.*')
                ->orderBy('stock_lotes.fecha_vencimiento','asc')
                ->get();        
        if(count($data)<1){
            return $data;
        }else{
        
        $nueva=$data[0]->cantidad;
        if($data[0]->cantidad>=$cantidad_prescrita){
            $datos = DB::table('stock_farmacias')
                ->join('stock_lotes', 'stock_farmacias.id', '=', 'stock_lotes.id_stock_farmacia')                                
                ->where('stock_farmacias.codigo_producto','=',$codigo_producto)
                ->where('stock_farmacias.id_producto','=',$id_producto)
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->where('stock_lotes.cantidad','>',0)
                ->where('stock_lotes.cantidad','=',$nueva)
                ->select('stock_farmacias.nombre_producto','stock_farmacias.codigo_producto',
                        'stock_lotes.*')
                ->orderBy('stock_lotes.fecha_vencimiento','asc')
                ->get(); 
            return $datos;
        }else{
            return $data;
        }
        }
    }
    
    public function ShowLotesByCodigo_productoAndId_farmacia($codigo_producto, $id_farmacia,$cantidad_prescrita){
        $producto = DB::table('productos')
                ->where('productos.codigo','=',$codigo_producto)
                ->get();
        $id_producto=$producto[0]->id;
        
        $data = DB::table('stock_farmacias')
                ->join('stock_lotes', 'stock_farmacias.id', '=', 'stock_lotes.id_stock_farmacia')                                
                ->where('stock_farmacias.codigo_producto','=',$codigo_producto)
                ->where('stock_farmacias.id_producto','=',$id_producto)
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->where('stock_lotes.cantidad','>',0)
                ->select('stock_farmacias.nombre_producto','stock_farmacias.codigo_producto',
                        'stock_lotes.*')
                ->orderBy('stock_lotes.fecha_vencimiento','asc')
                ->get();
        $nueva=$data[0]->cantidad;
        if($data[0]->cantidad>=$cantidad_prescrita){
            $datos = DB::table('stock_farmacias')
                ->join('stock_lotes', 'stock_farmacias.id', '=', 'stock_lotes.id_stock_farmacia')                                
                ->where('stock_farmacias.codigo_producto','=',$codigo_producto)
                ->where('stock_farmacias.id_producto','=',$id_producto)
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->where('stock_lotes.cantidad','>',0)
                ->where('stock_lotes.cantidad','=',$nueva)
                ->select('stock_farmacias.nombre_producto','stock_farmacias.codigo_producto',
                        'stock_lotes.*')
                ->orderBy('stock_lotes.fecha_vencimiento','asc')
                ->get(); 
            return $datos;
        }else{
            return $data;
        }               
    }
    
    public function ExisteStockFarmacia($codigo, $farmacia) {
        $data = Stock_farmacia::where('codigo_producto', '=', $codigo)
                ->where('nombre_farmacia', '=', $farmacia)
                ->first();
        return isset($data) ? true : false;
    }

    public function ExisteProdxCodigoAndIdfarmacia($codigo, $id_farmacia){
        $data = Stock_farmacia::where('codigo_producto', '=', $codigo)
                ->where('id_farmacia', '=', $id_farmacia)
                ->first();
        return $data;
    }
    
    public function ExisteProdxIdProductoAndIdfarmacia($id_producto, $id_farmacia){
        $data = Stock_farmacia::where('id_producto', '=', $id_producto)
                ->where('id_farmacia', '=', $id_farmacia)
                ->first();
        return $data;
    }
    
    
    
    public function GetStockByIdStockFarmacia($id){        
        $data = DB::table('stock_farmacias')
                ->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')  
                ->select('stock_farmacias.*', 'unidades.abreviatura')
                ->where('stock_farmacias.id','=',$id)
                ->first();
        return $data;
    }
    
    public function GetCantGeneralStockLoteByProd($id_stock_farmacia){
        $data = DB::table('stock_farmacias')
                ->join('stock_lotes', 'stock_farmacias.id', '=', 'stock_lotes.id_stock_farmacia')
                ->where('stock_farmacias.id', '=', $id_stock_farmacia)
                ->where('stock_lotes.cantidad', '>', 0)
                ->select('stock_farmacias.nombre_producto', 'stock_lotes.id_stock_farmacia',DB::raw('sum(stock_lotes.cantidad) as cantidad'))
                ->groupBy('stock_farmacias.nombre_producto', 'stock_lotes.id_stock_farmacia')
                ->get();
        return $data;
    }

    public function GetLotesById_stock_farmacia($id_stock_farmacia){
        $data = DB::table('stock_farmacias')
                ->join('stock_lotes', 'stock_farmacias.id', '=', 'stock_lotes.id_stock_farmacia')
                ->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')  
                ->where('stock_farmacias.id', '=', $id_stock_farmacia)
                ->where('stock_lotes.cantidad', '>=', 0)
                ->select('stock_farmacias.nombre_producto','stock_farmacias.codigo_producto','stock_farmacias.id_farmacia as id_farmacia',
                        'unidades.abreviatura','stock_lotes.*','productos.nombre as prod_farmaceutico')
                ->get();
        return $data;
    }

    public function GetProductosByFarmacia($id_farmacia) {
        $data = DB::table('stock_farmacias')
                ->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->where('stock_farmacias.id_farmacia', '=', $id_farmacia)
                ->select('productos.nombre', 'productos.codigo','productos.id','unidades.abreviatura')
                ->orderBy('productos.nombre', 'asc')
                ->get();
        return $data;
    }
    
    public function GetCantProdByFarmacia($id_farmacia){
        $data=DB::table('stock_farmacias')
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->count();
        //var_dump($data); exit;
        return $data;
    }

    public function GetCountConStock ($id_farmacia) {
        $data=DB::table('stock_farmacias')
				->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
                ->where('cantidad','>',100)
                ->where('id_farmacia','=',$id_farmacia)
                ->count();
        return $data;
    }
    public function GetConStock ($id_farmacia) {
        $data=DB::table('stock_farmacias')
                ->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->select('stock_farmacias.*','unidades.abreviatura','productos.nombre as prod_farmaceutico')
                ->where('cantidad','>',100)
                ->where('id_farmacia','=',$id_farmacia)
                ->get();
        return $data;
    }
    
    public function GetProductosByTipoProductoAndFarma ($id_farmacia,$tipo) {
        $data=DB::table('stock_farmacias')
                ->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->select('stock_farmacias.*','unidades.abreviatura','productos.nombre as prod_farmaceutico')
                //->where('cantidad','>',0)
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->where('productos.id_tipo','=',$tipo)
                ->get();
        return $data;
    }
    public function GetCountProductosByTipoProductoAndFarma ($id_farmacia,$tipo) {
        $data=DB::table('stock_farmacias')
                ->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
                //->where('cantidad','>',0)
                ->where('id_farmacia','=',$id_farmacia)
                ->where('productos.id_tipo','=',$tipo)
                ->count();
        return $data;
    }
    
    public function GetCountSinStock ($id_farmacia) {
        $data=DB::table('stock_farmacias')
				->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
                ->where('cantidad','=',0)
                ->where('id_farmacia','=',$id_farmacia)
                ->count();
        return $data;
    }
    public function GetSinStock ($id_farmacia) {
        $data=DB::table('stock_farmacias')
                ->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->select('stock_farmacias.*','unidades.abreviatura','productos.nombre as prod_farmaceutico')
                ->where('cantidad','=',0)
                ->where('id_farmacia','=',$id_farmacia)
                ->get();
        return $data;
    }
    
    public function GetMinStock ($id_farmacia) {
        $data=DB::table('stock_farmacias')
                ->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->select('stock_farmacias.*','unidades.abreviatura','productos.nombre as prod_farmaceutico')
                ->where('cantidad','<=',100)
                ->where('cantidad','>',0)
                ->where('id_farmacia','=',$id_farmacia)
                ->get();
        return $data;
    }

    public function GetCountMinimoStock ($id_farmacia) {
        $data=DB::table('stock_farmacias')
				->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
                ->where('cantidad','<=',100)
                ->where('cantidad','>',0)
                ->where('id_farmacia','=',$id_farmacia)
                ->count();
        return $data;
    }
    public function GetMinimoStock ($id_farmacia) {
        $data=DB::table('stock_farmacias')
				->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
                ->where('cantidad','<=',100)
                ->where('cantidad','>',0)
                ->where('id_farmacia','=',$id_farmacia)
                ->get();
        return $data;
    }
    
    public function ShowStockByFarmacia($nombre_farmacia) {
        $data = Stock_farmacia::where('nombre_farmacia', '=', $nombre_farmacia)
                ->get();
        return $data;
    }

    public function ShowStockByIdFarmacia($id_farmacia) {
        $data = Stock_farmacia::where('id_farmacia', '=', $id_farmacia)
                ->get();
        return $data;
    }

    public function ShowStockByIdFarmaciaAndCodigo($id_farmacia, $codigo_producto) {
        $data = DB::table('stock_farmacias')
                ->where('id_farmacia', '=', $id_farmacia)
                ->where('codigo_producto', '=', $codigo_producto)
                ->select(DB::raw('coalesce(sum(stock_farmacias.cantidad),0) as cantidad'))
                ->first();
        return $data;
    }

    public function ShowAll() {
        $datos = DB::table('stock_farmacias')
                ->select('stock_farmacias.*')
                ->get();
        return $datos;
    }

    public function GetStockMedicamento($id_farmacia, $codigo) {
        $cantidad = DB::table('stock_farmacias')
                ->select(DB::raw('coalesce(sum(stock_farmacias.cantidad),0) as cantidad'))
                ->where('id_farmacia', '=', $id_farmacia)
                ->where('codigo_producto', '=', $codigo)
                ->first();
        $datos = [$codigo, $cantidad];
        //var_dump($datos); exit;
        return $datos;
    }
    
     public function GetStockMedicamentoOtro($id_farmacia, $codigo) {
        $data=DB::table('stock_farmacias')
                ->join('productos','stock_farmacias.codigo_producto','=','productos.codigo')
                ->select(DB::raw('coalesce(sum(stock_farmacias.cantidad),0) as cantidad'),'productos.id as id_producto')
                ->where('stock_farmacias.id_farmacia', '=', $id_farmacia)
                ->where('stock_farmacias.codigo_producto', '=', $codigo)
                ->groupBy('productos.id')
                ->first();        
        return $data;
    }
    
    public function GetProductoById_farmaciaAndCodProduto($id_farmacia, $codigo){
        $data=DB::table('stock_farmacias')                
                ->where('stock_farmacias.id_farmacia', '=', $id_farmacia)
                ->where('stock_farmacias.codigo_producto', '=', $codigo)                
                ->first();
         return $data;
    }

public function GetProductoByServicio($id_farmacia, $id_consultorio, $fecha_inicio, $fecha_fin){
        
        $cad = "select 
P.codigo,P.nombre,U.abreviatura unidad,TP.nombre nombre_tipoproducto , sum(cantidad_dispensada) cantidad_dispensada, sum(cantidad_prescrita) cantidad_prescrita
from receta_vales RV
Inner Join producto_recetas PR On RV.id=PR.id_receta_vale
inner join productos P on P.id=PR.id_producto
inner join unidades U on P.id_unidad=U.id
inner join tipo_productos TP on P.id_tipo=TP.id
Inner Join farmacias FA On FA.id = RV.id_farmacia
Where RV.estado>0 and RV.id_farmacia=".$id_farmacia." and fecha_expedicion>='".$fecha_inicio."' AND fecha_expedicion<='".$fecha_fin."' and id_consultorio=".$id_consultorio."
         AND PR.id_producto in (Select id_producto From producto_servicios Where id_servicio=".$id_consultorio." and id_farmacia=".$id_farmacia.")
group by P.codigo,P.nombre,U.abreviatura,TP.nombre
Order by P.nombre asc";

        $data = DB::select($cad);
        
        return $data;

    }

    public function GetProductoByServicioFD($fechaDesde = "", $fechaHasta = "", $id_farmacia, $id_consultorio) {
        
        $cad = "Select * From sp_reporte_prescritos ('".$fechaDesde."','".$fechaHasta."',".$id_farmacia." ,".$id_consultorio.");";
        $data = DB::select($cad);

        return $data;
    }
    protected $fillable = ['codigo_producto', 'nombre_producto', 'cantidad', 'nombre_farmacia'];
	
	/*
	public function GetCountStockByIdproductoId_farmacia($id_producto,$id_farmacia) {
        $datos=DB::table('stock_farmacias') 
                ->where('stock_farmacias.id_producto','=',$id_producto)
                ->where('stock_farmacias.id_farmacia','=',$id_farmacia)
                ->count();         
        return $datos;
    }
	*/
	
	
}
