<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Totem extends Model
{
    public function buscar_cita_dia($id_asegurado, $totem_servicio) {
        $ids_consultorio = "";
        foreach($totem_servicio as $t):
            $ids_consultorio = $ids_consultorio.$t->id_consultorio.",";
        endforeach;
        if(strlen($ids_consultorio) > 0):
            $ids_consultorio = substr($ids_consultorio, 0, strlen($ids_consultorio)-1);
        else:
            $ids_consultorio = "0";
        endif;
        $cad = "select c.*, to_char(c.fecha_hora, 'HH24:MI PM') AS hora_cita, c2.nombre as servicio, coalesce(c.estado_triaje, '0') AS coalesce_estado_triaje, c2.has_triaje
                from citas c 
                inner join sub_consultorios sc on c.id_sub_consultorio = sc.id 
                inner join consultorios c2 on sc.id_consultorio = c2.id 
                where c.id_asegurado = ? and cast(c.fecha_hora as date) = cast(now() as date) and c.id_estado_reg = 1 and c.id_estado_cita = 1 and c2.id in(".$ids_consultorio.")
                order by c.fecha_hora desc";
        $data = DB::select($cad, [$id_asegurado]);
        return $data;
    }

    public function listar($p) {
        return $this->readFunctionPostgres('sp_listar_tvs', $p);
    }

    public function readFunctionPostgres($function, $parameters = null) {
        $_parameters = '';
        if (count($parameters) > 0) {
            $_parameters = implode("','", $parameters);
            $_parameters = "'" . $_parameters . "',";
        }
        //echo $_parameters;
        $data = DB::select("BEGIN;");
        $cad = "select " . $function . "(" . $_parameters . "'ref_cursor');";

        $data = DB::select($cad);
        $cad = "FETCH ALL IN ref_cursor;";
        $data = DB::select($cad);
        return $data;
    }

    public function getConsultoriosPorIdTotem($id_totem){
        $datos=DB::table('totems') 
                ->join('totem_servicios','totems.id','=','totem_servicios.id_totem')
                ->join('consultorios','totem_servicios.id_consultorio','=','consultorios.id')
                ->select('consultorios.*')
                ->where('totems.id','=',$id_totem)
                ->where('totem_servicios.id_estado','=','1')
                ->orderBy('totem_servicios.created_at','asc')
                ->get();
        return $datos;
    }

    public function getTotemOpcionesPorIdDispositivo($id_dispositivo){
        $datos=DB::table('totems') 
                ->join('totem_ip_configuraciones','totems.id','=','totem_ip_configuraciones.id_totem')
                ->join('totem_opciones','totem_ip_configuraciones.id_totem_opcion','=','totem_opciones.id')
                ->select('totem_opciones.id','totem_opciones.nombre')
                ->where('totems.id','=',$id_dispositivo)
                ->where('totem_opciones.estado','=','1')
                ->where('totem_ip_configuraciones.estado','=','1')
                ->orderBy('totem_opciones.nombre','asc')
                ->get();
        
        return $datos;
    }
}
