<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Asignacion_servicio extends Model
{

    /*public function ShowallById_Ipress($id_ipress){
		
		$cad = "select T1.*,T2.nombre piso,T3.nombre pabellon,T4.nombre consultorio
from asignacion_servicios T1
inner join pisos T2 on T1.id_piso=T2.id
inner join pabellones T3 on T2.id_pabellon=T3.id
inner join consultorios T4 on T1.id_consultorio=T4.id
where T1.id_establecimiento=".$id_ipress;
		$cad .= " And COALESCE(T1.estado,1)!=3  ";
        $data = DB::select($cad);
        return $data;
		
    }*/

public function ShowallById_Ipress($id_ipress){
    $cad = "select T1.*,T2.nombre piso,T3.nombre pabellon,T4.nombre consultorio,  T4.nombre  prioridad
            from asignacion_servicios T1
            inner join pisos T2 on T1.id_piso=T2.id
            inner join pabellones T3 on T2.id_pabellon=T3.id
            inner join consultorios T4 on T1.id_consultorio=T4.id
            where T1.id_establecimiento=".$id_ipress." 
                    And COALESCE(T1.estado,1)!=3 ";

$data = DB::select($cad);
        return $data;
    }


    public function GetNombreAsignacionServicios($id) {
        
        $cad = "select nombre
				from asignacion_servicios 
				where id_consultorio=".$id." 
				limit 1 ";
        $data = DB::select($cad);
        return $data[0];
    }

    public function GetAsignacionServiciosById($id) {
        
        $cad = "select T1.*,T2.id_pabellon
                from asignacion_servicios T1 
                inner join pisos T2 on T1.id_piso=T2.id
                where T1.id=".$id." 
                limit 1 ";
        $data = DB::select($cad);
        return $data[0];
    }

    public function GetPisoByIdPabellon($id_pabellon, $id_establecimiento) {
        
        $cad = "select * from pisos where id_pabellon=".$id_pabellon. " and id_establecimiento=".$id_establecimiento. " And estado=0";
        $data = DB::select($cad);
        return $data;
    }
	
    public function GetPiso($id_piso, $id_establecimiento) {
        
        $cad = "select * from pisos where id=".$id_piso. " and id_establecimiento=".$id_establecimiento. " And estado=0";
        $data = DB::select($cad);
        return $data;
    }
	
	public function ExisteAsignacionServicio($id_piso, $id_consultorio, $id_establecimiento, $id=0) {
        $data = Asignacion_servicio::where('id_piso', '=', $id_piso)->where('id_consultorio', '=', $id_consultorio)->where('id_establecimiento', '=', $id_establecimiento)->first();
        return isset($data) ? true : false;

    }

    public function ExisteServicio($id_piso, $id_consultorio, $id_establecimiento, $id=0) {
        $data = Asignacion_servicio::where('id_piso', '=', $id_piso)->where('id_consultorio', '=', $id_consultorio)->WhereNull('estado')->where('id_establecimiento', '=', $id_establecimiento)->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }
	
	
    
}
