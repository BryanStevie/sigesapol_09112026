<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use Carbon\Carbon;

class CanEstablecimiento extends Model
{
    
    public static function GetMedicamentos($id_can, $id_establecimiento) {
        $data=DB::table('can_ipress')
                    ->where('necesidad_anual','>',0)
                    ->where('id_can',$id_can) //cambiar 22
                    ->where('id_tipo_dispositivo',1)   
                    ->where('estado','<>',2)        
                    ->where('petitorio','=',1)     
                    ->where('id_servicio','=',0)
                    ->where('id_establecimiento',$id_establecimiento)//cambiar 1
                    ->orderby ('id_tipo_dispositivo','asc')   
                    ->get();
        return $data;
    }

    public static function GetMedicamentosServicios($id_can, $id_establecimiento,$id_servicio) {
        $data=DB::table('can_ipress')
                    ->where('necesidad_anual','>',0)
                    ->where('id_can',$id_can) //cambiar 22
                    ->where('id_tipo_dispositivo',1)   
                    ->where('estado','<>',2)        
                    ->where('petitorio','=',1)        
                    ->where('id_servicio',$id_servicio)//cambiar 1
                    ->where('id_establecimiento',$id_establecimiento)//cambiar 1
                    ->orderby ('id_tipo_dispositivo','asc')   
                    ->get();
        return $data;
    }

    public static function GetCantidadMedicamento($id_can, $id_establecimiento) {
        $num_estimaciones=DB::table('can_ipress')
                    ->where('necesidad_anual','>',0)
                    ->where('id_can',$id_can) //cambiar 22
                    ->where('id_tipo_dispositivo',1)
                    ->where('estado_necesidad',0)   
                    ->where('id_servicio','=',0)
                    ->where('id_establecimiento',$id_establecimiento)//cambiar 1
                    ->count();
        return $num_estimaciones;
    }

    public static function GetDispositivos($id_can, $id_establecimiento) {
        $data=DB::table('can_ipress')
                    ->where('necesidad_anual','>',0)
                    ->where('id_can',$id_can) //cambiar 22
                    ->where('id_tipo_dispositivo','>',1)
                    ->where('estado','<>',2)  
                    ->where('petitorio','=',1)        
                    ->where('id_establecimiento',$id_establecimiento)//cambiar 1
                    ->orderby ('id_tipo_dispositivo','asc')   
                    ->get();  
        return $data;
    }
    public static function GetCantidadDispositivo($id_can, $id_establecimiento) {
        $num_estimaciones=DB::table('can_ipress')
                    ->where('necesidad_anual','>',0)
                    ->where('id_can',$id_can) //cambiar 22
                    ->where('id_tipo_dispositivo','>',1)
                    ->where('estado','<>',2) 
                    ->where('petitorio','=',1)        
                    ->where('id_establecimiento',$id_establecimiento)//cambiar 1
                    ->count();  
        return $num_estimaciones;
    }

    public static function ContarProductos($id_can, $id_establecimiento, $tipo_dispositivo) {
        $cad = "select count(*) as total
            from can_ipress
            where estado <>2 and id_can ='{$id_can}' and id_establecimiento ='{$id_establecimiento}' ";
        
        if($tipo_dispositivo ==1):
            $cad .= " and id_tipo_dispositivo=1  "; 
        else:
            $cad .= " and id_tipo_dispositivo>1  ";
        endif;
        
        $data = DB::select($cad);
        if (isset($data[0]))
            return $data[0];
        else
            return $data;
    }

    public static function ProductosIngresados($id_can, $id_establecimiento, $tipo_dispositivo) {

        $data=DB::table('can_ipress')
                    ->where('id_can',$id_can) 
                    ->where('id_tipo_dispositivo',$tipo_dispositivo)
                    ->where('id_establecimiento',$id_establecimiento)
                    ->where('estado','<>',2)
                    ->orderby('descripcion','asc')
                    ->get();
        
        return $data;
    }

    public static function ProductosIngresados2y3($can_id, $establecimiento_id, $tipo_dispositivo) {

        $data=DB::table('can_ipress')
                    ->where('id_can',$can_id) 
                    ->where('id_tipo_dispositivo','>',1)   
                    ->where('id_establecimiento',$establecimiento_id)
                    ->where('estado','<>',2)
                    ->orderby('descripcion','asc')
                    ->get();
        
        return $data;
    }

}
