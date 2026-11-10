<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Consultorio_farmacia extends Model {

    public function ExisteFarmaciaServicio($id_farmacia, $id_servicio = 0) {
        $data = Consultorio_farmacia::where('id_farmacia', '=', $id_farmacia)->where('id_consultorio', '=', $id_servicio)->where('estado', '=', 1)->first();
        return isset($data) ? true : false;
    }

    public function ObtenerFarmaciaPorIdServicio($id_servicio = 0) {

        $cad = "Select cf.id, f.id id_farmacia, e.id id_establecimiento, f.nombre nom_farmacia, e.nombre nom_establecimiento, cf.estado From consultorio_farmacias cf
Inner Join farmacias f On cf.id_farmacia = f.id
Inner Join establecimientos e On f.id_establecimiento = e.id
Where id_consultorio=".$id_servicio."
Order By cf.estado Desc, e.nombre Asc";
        $data = DB::select($cad);
        return $data;
    }
	
	public function ObtenerFarmacias($id_servicio = 0,$id_farmacia = 0) {

        $cad = "Select cf.id, f.id id_farmacia, e.id id_establecimiento, f.nombre nom_farmacia, e.nombre nom_establecimiento, cf.estado From consultorio_farmacias cf
Inner Join farmacias f On cf.id_farmacia = f.id
Inner Join establecimientos e On f.id_establecimiento = e.id
Where id_farmacia=".$id_farmacia." and id_consultorio=".$id_servicio."
Order By cf.estado Desc, e.nombre Asc";
        $data = DB::select($cad);
        return $data;
    }

}
