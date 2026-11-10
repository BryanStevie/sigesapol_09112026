<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Producto_receta extends Model
{

    protected $table = 'producto_recetas';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id_receta_vale',
        'id_producto',
        'cantidad_prescrita',
        'cantidad_dispensada',
        'estado',
        'created_at',
        'updated_at',
        'saldo',
        'mes',
        'ano',
        'tiempo_periodo',
        'id_farmacia',
        'stock_almacen',
        'stock_establecimiento',
        'stock_farmacia',
        'posologia',
        'presentacion_comercial',
        'id_diagnosticos',
        'id_via_administracion',
        'id_observacion',
        'dia_farmacia',
        'receta_nro',
        'deleted_us',
        'deleted_at',
        'updated_us'
    ];
    //
	/*
    public function ShowMedicinasById_receta_vale($id){
        $datos=DB::table('producto_recetas') 
                ->join('productos','producto_recetas.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                //->join('stocks','productos.id','=','stocks.id_producto')
                ->where('producto_recetas.id_receta_vale','=',$id)
                ->select('producto_recetas.id as id_producto_receta','productos.*','productos.id as id_producto', 'producto_recetas.*','unidades.abreviatura')
                ->orderBy('productos.nombre','asc')
                ->get();
        //var_dump($datos); exit;
        return $datos;
    }
	*/
	public static function ShowMedicinasById_receta_vale($id){
        $datos=DB::table('producto_recetas') 
                ->join('productos','producto_recetas.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->leftJoin('diagnosticos', 'producto_recetas.id_diagnosticos', '=', 'diagnosticos.id')
				->leftJoin('farmacias','producto_recetas.id_farmacia','=','farmacias.id')
                ->leftJoin('via_administracion','producto_recetas.id_via_administracion','=','via_administracion.id')
                ->where('producto_recetas.estado','<>',0)
                ->where('producto_recetas.id_receta_vale','=',$id)
                ->select('producto_recetas.id as id_producto_receta','productos.codigo','productos.nombre','productos.id as id_producto','productos.presentacion_comercial as presentacion', 'producto_recetas.*','unidades.abreviatura','farmacias.nombre as farmacia','via_administracion.descripcion as via', 'diagnosticos.nombre as nombre_diagnostico','diagnosticos.codigo as cod_diagnostico','producto_recetas.id_via_administracion','producto_recetas.id_observacion','producto_recetas.dia_farmacia','producto_recetas.cantidad_prescrita','producto_recetas.cantidad_dispensada','producto_recetas.receta_nro')                
                ->orderBy('productos.nombre','asc')
                ->get();
        //var_dump($datos); exit;
        return $datos;
    }
  
    //luis rojas
    public static function get_diagnostico_nombre($id) {
        $diagnostico = DB::table('diagnosticos')
            ->where('id', '=', $id)
            ->select('codigo')
            ->first();
        if ($diagnostico) {
            return $diagnostico->codigo;
        } else {
            return "Diagnóstico no encontrado";
        }
    }

        //luis rojas
        public static function get_diagnostico_nombrexProcedimiento($id) {
            $diagnostico = DB::table('diagnosticos')
                ->where('id', '=', $id)
                ->select('nombre')
                ->first();
            if ($diagnostico) {
                return $diagnostico->nombre;
            } else {
                return "Diagnóstico no encontrado";
            }
        }
	
	public function ShowMedicinasById_receta_vale_resto($id) {
        $cad = "select producto_recetas.id as id_producto_receta,productos.*,productos.id as id_producto, producto_recetas.*,unidades.abreviatura,diagnosticos.nombre as nombre_diagnostico,diagnosticos.codigo as cod_diagnostico from producto_recetas
inner join productos on producto_recetas.id_producto=productos.id
inner join unidades on productos.id_unidad=unidades.id
left join diagnosticos on producto_recetas.id_diagnosticos = diagnosticos.id
where producto_recetas.id_receta_vale=".$id." and producto_recetas.estado <>0
and cantidad_dispensada=0
order By productos.nombre asc";

        $data = DB::select($cad);
        return $data;
    }
	

    public function receta()
    {
        return $this->belongsTo('App\Models\Receta_vale', 'id_receta_vale', 'id');
    }

    public static function ShowMedicinasById_receta_vale_cqanestesia($id){
        $datos=DB::table('producto_recetas') 
                ->join('productos','producto_recetas.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->leftJoin('diagnosticos', 'producto_recetas.id_diagnosticos', '=', 'diagnosticos.id')
				->leftJoin('farmacias','producto_recetas.id_farmacia','=','farmacias.id')
                ->leftJoin('via_administracion','producto_recetas.id_via_administracion','=','via_administracion.id')
                ->where('producto_recetas.id_receta_vale','=',$id)
                ->where('producto_recetas.estado','<>',0)
                ->select('producto_recetas.id as id_producto_receta','productos.codigo','productos.nombre','productos.id as id_producto','productos.presentacion_comercial as presentacion', 'producto_recetas.*','unidades.abreviatura','farmacias.nombre as farmacia','via_administracion.descripcion as via', 'diagnosticos.nombre as nombre_diagnostico','diagnosticos.codigo as cod_diagnostico','producto_recetas.id_via_administracion')                
                ->orderBy('productos.nombre','asc')
                ->get();
        //var_dump($datos); exit;
        return $datos;
    }
	
}
