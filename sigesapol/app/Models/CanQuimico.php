<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use Carbon\Carbon;

class CanQuimico extends Model
{
    public function listado_can_servicio_ajax($p){
        return $this->readFunctionPostgres('sp_listado_servicio_can',$p);
    }

    public function NombreCan($id_can) {
        $cad = "select cs.*
        from can_sigesapols cs
        where cs.estado_activo = 1 and cs.id = '".$id_can."'" ;
        $data = DB::select($cad);
        return $data[0];
    }

    public function ShowAll() {
        $cad = "select cs.*
        from can_sigesapols cs
        where cs.estado = 1
        order by cs.id desc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowAll2($id_establecimiento) {
        $cad = "select cs.id id, cs.estado_activo, cs.nombre_can,  mc.medicamento_cerrado, mc.dispositivo_cerrado, mc.fin_first_login
        from can_sigesapols cs
        inner join monitoreo_cans mc on mc.id_can = cs.id
        where cs.estado = 1 and mc.id_establecimiento = ".$id_establecimiento."
        and id_servicio = 0
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

    public function listar_can_productos_ajax($p){
        return $this->readFunctionPostgres('sp_listar_productos_can',$p);
    }

    public function ProductoCan($id) {
        $cad = "select ci.*
        from can_ipress ci
        where ci.id = ".$id."
        ";
        $data = DB::select($cad);
        return $data;
    }
}
