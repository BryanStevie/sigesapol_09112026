<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Producto_servicio extends Model
{

	
	/*public function ExisteProductoServicio($id_producto,$id_servicio=0,$id_farmacia) {
        $data = Producto_servicio::where('id_producto', '=', $id_producto)->where('id_servicio', '=', $id_servicio)->where('id_farmacia', '=', $id_farmacia)->first();
        return isset($data) ? true : false;
    }
	
	public function ObtenerProductoPorIdServicio($id_servicio=0,$id_farmacia){
        
		$cad = "select 
producto_servicios.*,productos.nombre,productos.codigo,consultorios.nombre servicio
from producto_servicios
inner join productos on producto_servicios.id_producto=productos.id
inner join consultorios on producto_servicios.id_servicio=consultorios.id
where producto_servicios.id_servicio=".$id_servicio." and producto_servicios.id_farmacia=".$id_farmacia."
and COALESCE(producto_servicios.estado,1)=1 
order by productos.nombre ";
        $data = DB::select($cad);
        return $data;
		
    }*/

	public function ExisteProductoServicio($id_producto,$id_servicio=0,$id_farmacia=0) {
        $data = Producto_servicio::where('id_producto', '=', $id_producto)->where('id_servicio', '=', $id_servicio)->where('id_farmacia', '=', $id_farmacia)->where('estado', '=', '1')->first();
        return isset($data) ? true : false;
    }
	
	public function ObtenerProductoPorIdServicio($id_servicio=0){
        
		$cad = "select 
producto_servicios.*,productos.nombre,productos.codigo,consultorios.nombre servicio
from producto_servicios
inner join productos on producto_servicios.id_producto=productos.id
inner join consultorios on producto_servicios.id_servicio=consultorios.id
where producto_servicios.id_servicio=".$id_servicio."
and COALESCE(producto_servicios.estado,1)=1 
order by productos.nombre ";
        $data = DB::select($cad);
        return $data;
		
    }

	public function listarProductoPorIdServicioIdFarmacia($id_farmacia=0, $id_servicio=0){
        $data = DB::select("Select * From sp_listar_productos_x_farmacia_consultorio(".$id_farmacia.", ".$id_servicio.");");
        return $data;
    }	
    
}
