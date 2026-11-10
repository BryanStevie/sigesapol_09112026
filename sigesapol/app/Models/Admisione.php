<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Admisione extends Model
{
	function getNroAdmision($id_establecimiento){
		$cad = "select extract(year from now())||LPAD(((count(*)+1)::text),5,'0')nro_admision 
from admisiones T1
inner join farmacias T2 on T1.id_farmacia = T2.id 
where T2.id_establecimiento = ".$id_establecimiento." and T1.estado = 1 
		/*and fecha_admision = now()::date*/ ";
		$data = DB::select($cad);
        return $data[0]->nro_admision;
	}
    //
	
   	public function GetAdmisionByNroAdmision($nro_admision,$id_establecimiento) {
        
        $cad = "SELECT T1.*,T2.nombre consultorio,(case when turno='M' then 'MA&Ntilde;ANA' when turno='T' then 'TARDE' when turno='N' then 'NOCHE' end)turnos
,T3.nombre,T3.paterno,T3.materno,T3.dni,T3.colegiatura
,coalesce((SELECT count(1) FROM receta_vales WHERE id_admision = T1.id AND estado=1),0)actual_receta
FROM admisiones T1
inner join consultorios T2 on T1.id_consultorio = T2.id
inner join medicos T3 on T1.id_medico = T3.id
inner join farmacias T4 on T1.id_farmacia = T4.id 
where T1.nro_admision = '".$nro_admision."'";
		
		$cad .= " AND T4.id_establecimiento=".$id_establecimiento;
		
        $data = DB::select($cad);
        return $data[0];
    }
    
	public function GetAdmisionByIdFarmaciaAndDni($id_establecimiento,$dni) {
        
         $cad = "SELECT T1.*
,coalesce(T1.cantidad_receta,1)cantidad_receta
,coalesce((SELECT count(1) FROM receta_vales WHERE id_admision = T1.id AND estado=1),0)actual_receta
,T2.nombre consultorio,(case when turno='M' then 'MA&Ntilde;ANA' when turno='T' then 'TARDE' when turno='N' then 'NOCHE' when turno='MD' then 'MADRUGADA' end)turnos
,T3.nombre,T3.paterno,T3.materno,T3.dni,T3.colegiatura
FROM admisiones T1
inner join consultorios T2 on T1.id_consultorio = T2.id
inner join medicos T3 on T1.id_medico = T3.id 
inner join farmacias T4 on T1.id_farmacia = T4.id 
where T1.dni_beneficiario = '".$dni."'
and T4.id_establecimiento = ".$id_establecimiento;
		
		$cad .= " And extract(day from (now()-T1.fecha_admision)) between 0 and 30 ";
		$cad .= " AND coalesce(T1.cantidad_receta,1) > coalesce((SELECT count(1) FROM receta_vales WHERE id_admision = T1.id AND estado=1),0)";
		$cad .= " ORDER BY T1.id desc ";
		//echo $cad;
        $data = DB::select($cad);
        return $data;
    }
	
	public function GetAdmisionById($id) {
        
         $cad = "SELECT T1.*
,T2.nombre consultorio,(case when turno='M' then 'MA&Ntilde;ANA' when turno='T' then 'TARDE' when turno='N' then 'NOCHE' when turno='MD' then 'MADRUGADA' end)turnos
,T3.nombre,T3.paterno,T3.materno,T3.dni,T3.colegiatura
,EXTRACT(YEAR FROM age(now(),T1.fecha_nacimiento_beneficiario))edad 
FROM admisiones T1
inner join consultorios T2 on T1.id_consultorio = T2.id
inner join medicos T3 on T1.id_medico = T3.id 
inner join farmacias T4 on T1.id_farmacia = T4.id 
where T1.id = ".$id;
		$cad .= " ORDER BY T1.id desc ";
        $data = DB::select($cad);
        return $data;
    }
	
	public function ExisteAdmision($dni_beneficiario,$fecha_admision,$tipo_admision,$id_consultorio,$turno,$id_medico) {
        $data = Admisione::where('dni_beneficiario', '=', $dni_beneficiario)->where('fecha_admision', '=', $fecha_admision)->where('tipo_admision', '=', $tipo_admision)->where('id_consultorio', '=', $id_consultorio)->where('turno', '=', $turno)->where('id_medico', '=', $id_medico)->first();
        return isset($data) ? true : false;
    }
	
	
}
