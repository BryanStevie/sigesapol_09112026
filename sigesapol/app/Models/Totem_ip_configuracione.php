<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Totem_ip_configuracione extends Model
{
    public function get_todos_opciones_totem_by_dispositivo($id_dispositivo) {

        $opciones = DB::table('totem_ip_configuraciones')
                ->join('totem_opciones', 'totem_ip_configuraciones.id_totem_opcion', '=', 'totem_opciones.id')
                ->select('totem_ip_configuraciones.id_totem_opcion','totem_opciones.nombre','totem_opciones.parent','totem_opciones.orden','totem_opciones.funcion')
                ->where('totem_ip_configuraciones.id_totem', $id_dispositivo)
                //->where('totem_opciones.parent', '0')
                ->where('totem_opciones.estado', '1')
                ->where('totem_ip_configuraciones.estado', '1')
                ->orderBy('totem_opciones.orden')
                ->get();

        return $opciones;
    }

    public function get_opciones_totem_by_dispositivo($id_dispositivo) {

        $opciones = DB::table('totem_ip_configuraciones')
                ->join('totem_opciones', 'totem_ip_configuraciones.id_totem_opcion', '=', 'totem_opciones.id')
                ->select('totem_ip_configuraciones.id_totem_opcion','totem_opciones.nombre','totem_opciones.parent','totem_opciones.orden','totem_opciones.funcion')
                ->where('totem_ip_configuraciones.id_totem', $id_dispositivo)
                ->where('totem_opciones.parent', '0')
                ->where('totem_opciones.estado', '1')
                ->where('totem_ip_configuraciones.estado', '1')
                ->orderBy('totem_opciones.orden')
                ->get();

        return $opciones;
    }

    public function get_opciones_totem_by_dispositivo_and_parent($id_dispositivo, $id_parent) {

        $opciones = DB::table('totem_ip_configuraciones')
                ->join('totem_opciones', 'totem_ip_configuraciones.id_totem_opcion', '=', 'totem_opciones.id')
                ->select('totem_ip_configuraciones.id_totem_opcion','totem_opciones.nombre','totem_opciones.parent','totem_opciones.orden','totem_opciones.funcion')
                ->where('totem_ip_configuraciones.id_totem', $id_dispositivo)
                ->where('totem_opciones.parent', $id_parent)
                ->where('totem_opciones.estado', '1')
                ->orderBy('totem_opciones.orden')
                ->get();

        return $opciones;
    }
}
