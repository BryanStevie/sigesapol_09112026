<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use Carbon\Carbon;

class Can extends Model
{
    public function NombreCan($id_can) {
        $cad = "select cs.*
        from can_sigesapols cs
        where cs.id = '".$id_can."'" ;
        $data = DB::select($cad);
        return $data[0];
    }

    public function BuscarCan($id_can) {
       $data = DB::table('can_sigesapols')                
                ->where('id',$id_can)
                ->orderby('id','Desc')                
                ->first(); 
        return $data;
    }

    public function ShowAll() {
        $cad = "select cs.*
        from can_sigesapols cs
        where cs.estado = 1
        order by cs.id desc";
        $data = DB::select($cad);
        return $data;
    }

    public function ExisteCan($nombre) {
        $cad = "select cs.*
        from can_sigesapols cs
        where cs.estado = 1 and cs.nombre_can = '".$nombre."'" ;
        $data = DB::select($cad);
        return $data;
    }

    public static function RegistrarCAN($id, $nombre, $id_user, $accion)
    {
        $cad = "Select sp_insert_can(?,?,?,?)";
        $data = DB::select($cad, array($id,$nombre, $id_user, $accion));
        return $data[0]->sp_insert_can;
    }

    public function BuscarCanServicioCan($id_can, $id_establecimiento, $id_servicio) {
        return $this->readFunctionPostgres('sp_listar_can_ipress_servicio',$p);
    }  

    public function readFunctionPostgres($function, $parameters = null){
    
      $_parameters = '';
      if (count($parameters) > 0) {
          $_parameters = implode("','", $parameters);
          $_parameters = "'" . $_parameters . "',";
      }
    $data = DB::select("BEGIN;");
    $cad = "select " . $function . "(" . $_parameters . "'ref_cursor3');";
    $data = DB::select($cad);
    $cad = "FETCH ALL IN ref_cursor3;";
    $data = DB::select($cad);
    $closeCursor = DB::select("CLOSE ref_cursor3;");
      return $data;
    }

    public static function CopiarProductosCanAnteriorNivel1($can_id, $tipo, $establecimiento_id) {

        $cad = "Select sp_copiar_can_anterior_nivel_1(?,?,?)";
        $data = DB::select($cad, array($can_id, $tipo, $establecimiento_id));
        
        
        return $data[0];

    }

    public static function CopiarProductosCanAnteriorServicioNivel2y3($can_id, $tipo, $establecimiento_id, $servicio_id) {

        $cad = "Select sp_copiar_can_anterior_nivel_2y3(?,?,?,?)";
        $data = DB::select($cad, array($can_id, $tipo, $establecimiento_id, $servicio_id));
        
        
        return $data[0];

    }

    public function ConsolidadoCANxNivel($id_can, $id_tipo_producto) {
        $data = DB::select("select * From sp_consolidado_can_nivel(" . $id_can . "," . $id_tipo_producto . ");");
        return $data;
    }
}
