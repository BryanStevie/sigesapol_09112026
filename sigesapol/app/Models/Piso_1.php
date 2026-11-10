<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Piso extends Model {

    public function ShowallById_Ipress($id_ipress) {

        $cad = "select T1.*,T2.nombre pabellon
from pisos T1
inner join pabellones T2 on T1.id_pabellon=T2.id
where T1.id_establecimiento=" . $id_ipress;
        $cad .= " And COALESCE(T1.estado,1)!=3  ";
        $data = DB::select($cad);
        return $data;
    }
/*
    public function GetPisoByIdCuarto($id_cuarto) {
        
        $cad = "select *
                from pisos T1
                where id_cuarto=".$id_cuarto;
        $data = DB::select($cad);
        return $data;
    }
    */

        public function GetPisoById($id) {
        
        $cad = "select T1.*,T2.id_establecimiento
                from pisos T1 
                inner join pabellones T2 on T2.id=T1.id_pabellon
                where T1.id=".$id." 
                limit 1 ";
        $data = DB::select($cad);
        return $data[0];
    }


    public function ExistePiso($nombre, $id_pabellon, $id_establecimiento, $id = 0) {
        $data = Piso::where(DB::raw('UPPER(nombre)'), '=', trim(strtoupper($nombre)))->where('id_pabellon', '=', $id_pabellon)->whereNull('estado')->where('id_establecimiento', '=', $id_establecimiento)->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }

    public function GetPabellonbyAtencion($atencion, $id_establecimiento) {
        
        $cad = "select id, nombre from pabellones where nombre_atencion='".$atencion. "' and id_establecimiento= ".$id_establecimiento." And estado=0";
        $data = DB::select($cad);
        return $data;
    }

    public function GetPisoyPrioridad($id_piso, $id_establecimiento, $nombre_atencion) {
        
        $cad = "select p.id, p.descripcion prioridad from asignacion_servicios a 
        inner join prioridades p on a.id_prioridad = p.id
        where a.id_piso='".$id_piso. "' and a.id_establecimiento= ".$id_establecimiento." and a.nombre_atencion= '".$nombre_atencion."' And a.estado=0 group by p.descripcion,p.id";
        $data = DB::select($cad);
        return $data;
    }


    public function GetPrioridadbyTopico($id_piso, $id_establecimiento, $nombre_atencion, $prioridad) {
        
        $cad = "select a.id, p.nombre consultorio 
                from asignacion_servicios a 
                inner join consultorios p on a.id_consultorio = p.id
                where a.id_piso='".$id_piso. "' and a.id_establecimiento= ".$id_establecimiento." and a.nombre_atencion= '".$nombre_atencion."' and a.id_prioridad= ".$prioridad." And a.estado=0";
        
        $data = DB::select($cad);
        return $data;
    }

 
}
